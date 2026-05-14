<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentRequest;
use App\Models\Request as CitizenRequest;
use Illuminate\Support\Facades\Storage;

class RequestPdfGenerator
{
    public function __construct(private readonly SimplePdfService $pdfService)
    {
    }

    public function generateForCompletedPaidRequest(CitizenRequest $request): void
    {
        $request->loadMissing(['user', 'service.office.municipality', 'payment', 'reviewer', 'status']);

        if (strtolower((string) $request->status?->name) !== 'completed') {
            return;
        }

        if (strtolower((string) $request->payment?->status) !== 'completed') {
            return;
        }

        $definitions = [
            'Generated Certificate PDF' => [
                'file' => 'certificate.pdf',
                'title' => 'GovEase Service Certificate',
                'lines' => [
                    'Tracking Number: ' . $request->tracking_number,
                    'Citizen: ' . ($request->user?->full_name ?? '-'),
                    'Office: ' . ($request->service?->office?->name ?? '-'),
                    'Municipality: ' . ($request->service?->office?->municipality?->name ?? '-'),
                    'Service: ' . ($request->service?->name ?? '-'),
                    'Status: Completed',
                    'Issued At: ' . now()->format('M d, Y h:i A'),
                ],
            ],
            'Generated Payment Receipt PDF' => [
                'file' => 'payment-receipt.pdf',
                'title' => 'GovEase Payment Receipt',
                'lines' => [
                    'Tracking Number: ' . $request->tracking_number,
                    'Citizen: ' . ($request->user?->full_name ?? '-'),
                    'Service: ' . ($request->service?->name ?? '-'),
                    'Amount Paid: $' . number_format((float) ($request->payment?->amount ?? 0), 2),
                    'Payment Method: ' . ($request->payment?->payment_method ?? '-'),
                    'Reference: ' . ($request->payment?->transaction_reference ?? '-'),
                    'Paid At: ' . optional($request->payment?->created_at)->format('M d, Y h:i A'),
                ],
            ],
            'Generated Approval PDF' => [
                'file' => 'approval.pdf',
                'title' => 'GovEase Service Approval',
                'lines' => [
                    'Tracking Number: ' . $request->tracking_number,
                    'Approved Service: ' . ($request->service?->name ?? '-'),
                    'Office: ' . ($request->service?->office?->name ?? '-'),
                    'Approved By: ' . ($request->reviewer?->full_name ?? 'Office Staff'),
                    'Final Status: Completed',
                    'Completion Date: ' . optional($request->updated_at)->format('M d, Y h:i A'),
                ],
            ],
        ];

        foreach ($definitions as $documentType => $definition) {
            $filePath = 'generated-documents/request-' . $request->id . '/' . $definition['file'];
            Storage::disk('public')->put(
                $filePath,
                $this->pdfService->makeTextPdf($definition['title'], $definition['lines'])
            );

            $document = Document::updateOrCreate(
                ['file_path' => $filePath],
                [
                    'document_type' => $documentType,
                    'uploaded_by' => $request->reviewed_by ?? $request->user_id,
                ]
            );

            DocumentRequest::firstOrCreate([
                'request_id' => $request->id,
                'document_id' => $document->id,
            ]);
        }
    }
}
