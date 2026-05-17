<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class DocumentReaderService
{
    private string $storageFolder;

    public function __construct()
    {
        $this->storageFolder = storage_path('app/document-reader');
    }

    public function ensureStorageFolder(): void
    {
        if (! is_dir($this->storageFolder)) {
            mkdir($this->storageFolder, 0755, true);
        }
    }

    public function storeUploadedFile(UploadedFile $file): string
    {
        $this->ensureStorageFolder();

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $fileName = Str::uuid()->toString().'.'.$extension;

        $file->move($this->storageFolder, $fileName);

        return $fileName;
    }

    public function analyzeDocument(string $fileName): array
    {
        $path = $this->filePath($fileName);

        if (! file_exists($path)) {
            return $this->errorAnalysis('Document file was not found on the server.');
        }

        $apiKey = config('services.gemini.api_key');

        if (empty($apiKey)) {
            return $this->errorAnalysis('Document AI is not configured. Contact support or register manually.');
        }

        $mimeType = $this->mimeTypeForFile($fileName);

        if ($mimeType === null) {
            return $this->errorAnalysis('Unsupported file type. Use PDF, JPG, JPEG, PNG, or WEBP.');
        }

        try {
            $analysis = $this->requestGeminiAnalysis($path, $mimeType);
        } catch (\Throwable $exception) {
            report($exception);

            return $this->errorAnalysis('AI analysis failed. Please try again with a clearer image or register manually.');
        }

        $this->saveAnalysis($fileName, $analysis);

        return $analysis;
    }

    /**
     * @return array{firstName: string, lastName: string}
     */
    public function extractRegistrationDetails(array $analysis): array
    {
        $fields = is_array($analysis['fields'] ?? null) ? $analysis['fields'] : [];

        $firstName = $this->pickFieldValue($fields, [
            'first_name',
            'given_name',
            'firstname',
            'forename',
        ]);

        $lastName = $this->pickFieldValue($fields, [
            'last_name',
            'surname',
            'family_name',
            'lastname',
        ]);

        if (($firstName === '' || $lastName === '') && ! empty($fields['full_name'])) {
            [$parsedFirst, $parsedLast] = $this->splitFullName((string) $fields['full_name']);
            $firstName = $firstName !== '' ? $firstName : $parsedFirst;
            $lastName = $lastName !== '' ? $lastName : $parsedLast;
        }

        return [
            'firstName' => $this->normalizePersonName($firstName),
            'lastName' => $this->normalizePersonName($lastName),
        ];
    }

    public function saveAnalysis(string $fileName, array $analysis): void
    {
        $this->ensureStorageFolder();
        file_put_contents(
            $this->analysisPath($fileName),
            json_encode($analysis, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function loadAnalysis(string $fileName): ?array
    {
        $path = $this->analysisPath($fileName);

        if (! file_exists($path)) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : null;
    }

    public function saveUploaderMetadata(string $fileName, User $user): void
    {
        $this->ensureStorageFolder();

        $metadata = [
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'role' => $user->role?->name,
            'uploaded_at' => now()->toDateTimeString(),
        ];

        file_put_contents(
            $this->uploaderPath($fileName),
            json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function loadUploaderMetadata(string $fileName): ?array
    {
        $path = $this->uploaderPath($fileName);

        if (! file_exists($path)) {
            return null;
        }

        $decoded = json_decode((string) file_get_contents($path), true);

        return is_array($decoded) ? $decoded : null;
    }

    public function deleteDocument(string $fileName): void
    {
        $fileName = basename($fileName);

        foreach ([$this->filePath($fileName), $this->analysisPath($fileName), $this->uploaderPath($fileName)] as $path) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }

    private function requestGeminiAnalysis(string $path, string $mimeType): array
    {
        $model = config('services.gemini.model', 'gemini-2.0-flash');
        $apiKey = config('services.gemini.api_key');

        $prompt = <<<'PROMPT'
You are analyzing an identity document such as a passport, national ID, driver's license, or residence permit.

Extract all visible information and respond with ONLY valid JSON using this exact schema:
{
  "status": "success",
  "document_type": "passport|national_id|driver_license|residence_card|birth_certificate|civil_document|unknown",
  "confidence": 0,
  "message": "",
  "fields": {
    "first_name": "",
    "last_name": "",
    "full_name": "",
    "date_of_birth": "",
    "document_number": "",
    "nationality": "",
    "expiry_date": "",
    "address": ""
  },
  "notes": []
}

Rules:
- Use status "error" only when the file is unreadable or not an identity document.
- confidence is an integer from 0 to 100.
- Put human names in first_name and last_name when visible; use full_name only when the document shows one combined name.
- Leave unknown field values as empty strings.
- notes must be an array of short strings.
PROMPT;

        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $model,
            $apiKey
        );

        $response = Http::timeout(120)->post($url, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => base64_encode((string) file_get_contents($path)),
                            ],
                        ],
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'temperature' => 0.2,
            ],
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Gemini API request failed: '.$response->body());
        }

        $text = data_get($response->json(), 'candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Gemini API returned an empty response.');
        }

        $analysis = json_decode(trim($text), true);

        if (! is_array($analysis)) {
            throw new RuntimeException('Gemini API returned invalid JSON.');
        }

        $analysis['fields'] = is_array($analysis['fields'] ?? null) ? $analysis['fields'] : [];
        $analysis['notes'] = is_array($analysis['notes'] ?? null) ? $analysis['notes'] : [];
        $analysis['confidence'] = (int) ($analysis['confidence'] ?? 0);
        $analysis['status'] = (string) ($analysis['status'] ?? 'unknown');
        $analysis['document_type'] = (string) ($analysis['document_type'] ?? 'unknown');

        return $analysis;
    }

    private function pickFieldValue(array $fields, array $keys): string
    {
        foreach ($keys as $key) {
            $value = $fields[$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return trim($value);
            }
        }

        return '';
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitFullName(string $fullName): array
    {
        $fullName = preg_replace('/\s+/', ' ', trim($fullName)) ?? '';

        if ($fullName === '') {
            return ['', ''];
        }

        $parts = explode(' ', $fullName);

        if (count($parts) === 1) {
            return [$parts[0], ''];
        }

        $lastName = array_pop($parts);

        return [implode(' ', $parts), $lastName];
    }

    private function normalizePersonName(string $name): string
    {
        $name = preg_replace('/\s+/', ' ', trim($name)) ?? '';

        if ($name === '') {
            return '';
        }

        return mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');
    }

    private function mimeTypeForFile(string $fileName): ?string
    {
        return match (strtolower(pathinfo($fileName, PATHINFO_EXTENSION))) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            default => null,
        };
    }

    private function filePath(string $fileName): string
    {
        return $this->storageFolder.'/'.basename($fileName);
    }

    private function analysisPath(string $fileName): string
    {
        return $this->storageFolder.'/'.basename($fileName).'.analysis.json';
    }

    private function uploaderPath(string $fileName): string
    {
        return $this->storageFolder.'/'.basename($fileName).'.uploader.json';
    }

    private function errorAnalysis(string $message): array
    {
        return [
            'status' => 'error',
            'document_type' => 'unknown',
            'confidence' => 0,
            'message' => $message,
            'fields' => [],
            'notes' => [],
        ];
    }
}
