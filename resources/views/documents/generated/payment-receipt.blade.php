<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generated Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f6fb; color: #0f172a; margin: 0; padding: 32px; }
        .page { max-width: 900px; margin: 0 auto; background: #fff; border: 1px solid #dbe3ef; border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08); }
        .eyebrow { color: #0f766e; font-size: 12px; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; }
        h1 { margin: 10px 0 8px; font-size: 34px; }
        .subtitle { margin: 0 0 28px; color: #475569; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border-bottom: 1px solid #e2e8f0; padding: 14px 0; text-align: left; font-size: 15px; }
        th { color: #64748b; font-weight: 700; width: 32%; }
        .amount { margin-top: 24px; padding: 18px 22px; border-radius: 12px; background: #ecfeff; border: 1px solid #a5f3fc; display: flex; justify-content: space-between; align-items: center; }
        .amount strong { font-size: 24px; }
        .footer { margin-top: 28px; color: #64748b; font-size: 14px; }
        @media print { body { background: #fff; padding: 0; } .page { box-shadow: none; border: none; max-width: none; } }
    </style>
</head>
<body>
    <div class="page">
        <div class="eyebrow">GovEase Receipt</div>
        <h1>Payment Receipt</h1>
        <p class="subtitle">Receipt for the completed payment associated with this service request.</p>

        <table>
            <tr><th>Tracking Number</th><td>{{ $requestData->tracking_number }}</td></tr>
            <tr><th>Citizen</th><td>{{ $requestData->user?->full_name ?? '-' }}</td></tr>
            <tr><th>Service</th><td>{{ $requestData->service?->name ?? '-' }}</td></tr>
            <tr><th>Office</th><td>{{ $requestData->service?->office?->name ?? '-' }}</td></tr>
            <tr><th>Payment Method</th><td>{{ $requestData->payment?->payment_method ?? '-' }}</td></tr>
            <tr><th>Transaction Reference</th><td>{{ $requestData->payment?->transaction_reference ?? '-' }}</td></tr>
            <tr><th>Paid On</th><td>{{ $requestData->payment?->created_at?->format('M d, Y h:i A') ?? '-' }}</td></tr>
        </table>

        <div class="amount">
            <span>Total Amount Paid</span>
            <strong>${{ number_format((float) ($requestData->payment?->amount ?? 0), 2) }}</strong>
        </div>

        <p class="footer">Document Type: {{ $document->document_type }}</p>
    </div>
</body>
</html>
