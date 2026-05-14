<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Certificate</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f6fb; color: #0f172a; margin: 0; padding: 32px; }
        .page { max-width: 900px; margin: 0 auto; background: #fff; border: 1px solid #dbe3ef; border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        .eyebrow { color: #4338ca; font-size: 12px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; }
        h1 { margin: 10px 0 8px; font-size: 34px; }
        .subtitle { margin: 0 0 28px; color: #475569; }
        .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 28px; }
        .panel { border: 1px solid #dbe3ef; border-radius: 12px; padding: 16px 18px; background: #f8fafc; }
        .label { display: block; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 8px; }
        .value { font-size: 16px; font-weight: 600; color: #0f172a; }
        .message { border: 1px solid #c7d2fe; background: #eef2ff; border-radius: 12px; padding: 18px; line-height: 1.7; }
        .footer { margin-top: 28px; color: #64748b; font-size: 14px; }
        @media print { body { background: #fff; padding: 0; } .page { box-shadow: none; border: none; max-width: none; } }
    </style>
</head>
<body>
    <div class="page">
        <div class="eyebrow">GovEase Certificate</div>
        <h1>Service Completion Certificate</h1>
        <p class="subtitle">This certificate confirms that the requested municipal service has been completed successfully.</p>

        <div class="grid">
            <div class="panel"><span class="label">Tracking Number</span><span class="value">{{ $requestData->tracking_number }}</span></div>
            <div class="panel"><span class="label">Citizen</span><span class="value">{{ $requestData->user?->full_name ?? '-' }}</span></div>
            <div class="panel"><span class="label">Municipality</span><span class="value">{{ $requestData->service?->office?->municipality?->name ?? '-' }}</span></div>
            <div class="panel"><span class="label">Office</span><span class="value">{{ $requestData->service?->office?->name ?? '-' }}</span></div>
            <div class="panel"><span class="label">Service</span><span class="value">{{ $requestData->service?->name ?? '-' }}</span></div>
            <div class="panel"><span class="label">Issued On</span><span class="value">{{ $document->created_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A') }}</span></div>
        </div>

        <div class="message">
            This document certifies that the service request listed above has reached the <strong>Completed</strong> stage and is recognized by the responsible office within GovEase.
        </div>

        <p class="footer">Document Type: {{ $document->document_type }}</p>
    </div>
</body>
</html>
