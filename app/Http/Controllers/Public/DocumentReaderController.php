<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class DocumentReaderController extends Controller
{
    public function create()
    {
        return view('documents.upload');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:15360',
        ], [
            'document.required' => 'Please choose a document.',
            'document.file' => 'The uploaded item must be a file.',
            'document.mimes' => 'Only PDF and image files are allowed. DOCX files are not accepted.',
            'document.max' => 'The file must not be larger than 15 MB.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('document.reader.create')
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('document');

        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp'];
        $allowedMimeTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (!in_array($extension, $allowedExtensions) || !in_array($mimeType, $allowedMimeTypes)) {
            return redirect()
                ->route('document.reader.create')
                ->withErrors([
                    'document' => 'Only PDF, JPG, JPEG, PNG, and WEBP files are accepted. DOCX files are rejected.',
                ])
                ->withInput();
        }

        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());

        $folder = storage_path('app/document-reader');

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $file->move($folder, $fileName);

        $path = $folder . DIRECTORY_SEPARATOR . $fileName;

        $analysis = $this->analyzeDocumentWithGemini($path, $mimeType);

        $this->saveAnalysis($fileName, $analysis);

        return redirect()
            ->route('document.reader.create')
            ->with('success', 'Document uploaded and analyzed successfully.')
            ->with('uploadedFile', $fileName)
            ->with('analysis', $analysis);
    }

    public function index(Request $request)
    {
        if (!$this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $folder = storage_path('app/document-reader');

        $documents = [];
        $previewDocument = null;

        if (is_dir($folder)) {
            $files = glob($folder . '/*');

            foreach ($files as $file) {
                if (is_file($file)) {
                    $fileName = basename($file);
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    $canPreview = false;
                    $type = 'Document';

                    if ($extension == 'pdf') {
                        $canPreview = true;
                        $type = 'PDF';
                    }

                    if (
                        $extension == 'jpg' ||
                        $extension == 'jpeg' ||
                        $extension == 'png' ||
                        $extension == 'webp'
                    ) {
                        $canPreview = true;
                        $type = 'Image';
                    }

                    $document = [
                        'name' => $fileName,
                        'size' => round(filesize($file) / 1024, 2) . ' KB',
                        'extension' => $extension,
                        'type' => $type,
                        'canPreview' => $canPreview,
                        'analysis' => $this->loadAnalysis($fileName),
                    ];

                    $documents[] = $document;

                    if ($request->query('preview') == $fileName && $canPreview == true) {
                        $previewDocument = $document;
                    }
                }
            }
        }

        return view('documents.reader', [
            'documents' => $documents,
            'previewDocument' => $previewDocument,
        ]);
    }

    public function preview($fileName)
    {
        if (!$this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (!file_exists($path)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (
            $extension == 'pdf' ||
            $extension == 'jpg' ||
            $extension == 'jpeg' ||
            $extension == 'png' ||
            $extension == 'webp'
        ) {
            return response()->file($path);
        }

        return redirect()->route('admin.document.reader');
    }

    public function download($fileName)
    {
        if (!$this->isAdministrator()) {
            abort(403, 'Unauthorized');
        }

        $fileName = basename($fileName);
        $path = storage_path('app/document-reader/' . $fileName);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    private function analyzeDocumentWithGemini($path, $mimeType)
    {
        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-3.1-flash-lite');

        if (empty($apiKey)) {
            return [
                'status' => 'error',
                'message' => 'Gemini API key is not configured.',
                'document_type' => 'unknown',
                'confidence' => 0,
                'fields' => [],
                'notes' => [],
            ];
        }

        $base64File = base64_encode(file_get_contents($path));

        $response = Http::withHeaders([
            'x-goog-api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ])
            ->timeout(120)
            ->post('https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent', [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $this->documentExtractionPrompt(),
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $mimeType,
                                    'data' => $base64File,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->failed()) {
            return [
                'status' => 'error',
                'message' => 'Gemini request failed: ' . $response->body(),
                'document_type' => 'unknown',
                'confidence' => 0,
                'fields' => [],
                'notes' => [],
            ];
        }

        $text = $this->extractGeminiText($response->json());

        if (empty($text)) {
            return [
                'status' => 'error',
                'message' => 'Gemini did not return readable text.',
                'document_type' => 'unknown',
                'confidence' => 0,
                'fields' => [],
                'notes' => [],
            ];
        }

        $text = trim($text);
        $text = preg_replace('/^```json\s*/i', '', $text);
        $text = preg_replace('/^```\s*/i', '', $text);
        $text = preg_replace('/```$/', '', $text);
        $text = trim($text);

        $decoded = json_decode($text, true);

        if (!is_array($decoded)) {
            return [
                'status' => 'error',
                'message' => 'Gemini returned text, but it was not valid JSON.',
                'document_type' => 'unknown',
                'confidence' => 0,
                'fields' => [],
                'notes' => [
                    $text,
                ],
            ];
        }

        return array_merge([
            'status' => 'ok',
            'document_type' => 'unknown',
            'confidence' => 0,
            'fields' => [],
            'notes' => [],
        ], $decoded);
    }

    private function documentExtractionPrompt()
    {
        return '
You are reading a government identity document such as a passport, national ID, residence card, driver license, birth certificate, or civil document.

Extract only information that is clearly visible in the uploaded document.

Return ONLY valid JSON.
Do not write markdown.
Do not explain outside the JSON.
Use null for missing fields.
Do not invent missing information.

Expected JSON structure:

{
  "status": "ok",
  "document_type": "passport | national_id | driver_license | residence_card | birth_certificate | civil_document | unknown",
  "confidence": 0,
  "fields": {
    "first_name": null,
    "last_name": null,
    "full_name": null,
    "father_name": null,
    "mother_name": null,
    "date_of_birth": null,
    "place_of_birth": null,
    "nationality": null,
    "gender": null,
    "marital_status": null,
    "document_number": null,
    "id_number": null,
    "passport_number": null,
    "registry_number": null,
    "record_number": null,
    "issue_date": null,
    "expiry_date": null,
    "issuing_authority": null,
    "country": null,
    "address": null
  },
  "notes": []
}

Rules:
- If it is a passport, prioritize passport_number, nationality, date_of_birth, issue_date, expiry_date, issuing_authority, country.
- If it is a national ID, prioritize id_number, first_name, last_name, father_name, mother_name, date_of_birth, registry_number, record_number.
- If it is a driver license, prioritize license/document number, full name, date_of_birth, issue_date, expiry_date.
- If a field is not visible, put null.
- confidence must be a number from 0 to 100.
';
    }

    private function extractGeminiText($data)
    {
        if (!isset($data['candidates'][0]['content']['parts'])) {
            return null;
        }

        $text = '';

        foreach ($data['candidates'][0]['content']['parts'] as $part) {
            if (isset($part['text'])) {
                $text .= $part['text'];
            }
        }

        return $text;
    }

    private function saveAnalysis($fileName, $analysis)
    {
        $folder = storage_path('app/document-reader-analysis');

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        file_put_contents(
            $folder . DIRECTORY_SEPARATOR . $fileName . '.json',
            json_encode($analysis, JSON_PRETTY_PRINT)
        );
    }

    private function loadAnalysis($fileName)
    {
        $path = storage_path('app/document-reader-analysis/' . $fileName . '.json');

        if (!file_exists($path)) {
            return null;
        }

        $analysis = json_decode(file_get_contents($path), true);

        if (!is_array($analysis)) {
            return null;
        }

        return $analysis;
    }

    private function isAdministrator()
    {
        return Auth::check()
            && Auth::user()->role
            && Auth::user()->role->name == 'Administrator';
    }
}
