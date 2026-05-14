<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Approval</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f6fb; color: #0f172a; margin: 0; padding: 32px; }
        .page { max-width: 900px; margin: 0 auto; background: #fff; border: 1px solid #dbe3ef; border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        .eyebrow { color: #7c3aed; font-size: 12px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; }
        h1 { margin: 10px 0 8px; font-size: 34px; }
        .subtitle { margin: 0 0 28px; color: #475569; }
        .summary { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .item { border: 1px solid #dbe3ef; border-radius: 12px; padding: 16px 18px; background: #faf5ff; }
        .item small { display: block; color: #6b7280; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .item strong { font-size: 16px; color: #0f172a; }
        .approval-note { margin-top: 28px; border-left: 4px solid #8b5cf6; background: #f5f3ff; padding: 18px 20px; line-height: 1.7; }
        .footer { margin-top: 28px; color: #64748b; font-size: 14px; }
        @media print { body { background: #fff; padding: 0; } .page { box-shadow: none; border: none; max-width: none; } }
    </style>
</head>
<body>
    <div class="page">
        <div class="eyebrow">GovEase Approval</div>
        <h1>Service Approval Notice</h1>
        <p class="subtitle">Final confirmation that the requested service was approved and completed.</p>

        <div class="summary">
            <div class="item"><small>Tracking Number</small><strong>{{ $requestData->tracking_number }}</strong></div>
            <div class="item"><small>Final Status</small><strong>{{ $requestData->status?->name ?? 'Completed' }}</strong></div>
            <div class="item"><small>Citizen</small><strong>{{ $requestData->user?->full_name ?? '-' }}</strong></div>
            <div class="item"><small>Approved By</small><strong>{{ $requestData->reviewer?->full_name ?? 'Office Staff' }}</strong></div>
            <div class="item"><small>Office</small><strong>{{ $requestData->service?->office?->name ?? '-' }}</strong></div>
            <div class="item"><small>Completion Date</small><strong>{{ $requestData->updated_at?->format('M d, Y h:i A') ?? '-' }}</strong></div>
        </div>

        <div class="approval-note">
            The office has completed its review and approval process for the requested document service. This approval notice may be used together with the generated certificate and receipt as part of the request record.
        </div>

        <p class="footer">Document Type: {{ $document->document_type }}</p>
    </div>
</body>
</html>
