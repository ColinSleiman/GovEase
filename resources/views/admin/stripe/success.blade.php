@extends('layouts.admin')

@section('title', 'Payment Successful | GovEase Admin')

@section('content')
<div class="admin-page">
    <div class="card-padded" style="max-width:480px; text-align:center; padding-top:48px; padding-bottom:48px;">

        {{-- Success Icon --}}
        <div style="width:72px; height:72px; background:#dcfce7; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 20px;">
            <svg width="36" height="36" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>

        <h1 class="admin-page-title" style="margin-bottom:8px;">Payment Successful!</h1>
        <p class="admin-page-subtitle" style="margin-bottom:32px;">
            Your payment has been processed successfully. A confirmation receipt has been sent to your email.
        </p>

        <div style="background:#f8fafc; border-radius:10px; padding:20px; margin-bottom:32px; text-align:left;">
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280; margin-bottom:10px;">
                <span>Status</span>
                <span style="color:#16a34a; font-weight:600;">✓ Paid</span>
            </div>
            @if(request('session_id'))
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280; margin-bottom:10px;">
                <span>Session ID</span>
                <span style="font-family:monospace; font-size:12px; color:#374151;">{{ request('session_id') }}</span>
            </div>
            @endif
            <hr style="border:none; border-top:1px solid #e5e7eb; margin:10px 0;">
            <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#111827;">
                <span>Total Paid</span>
                <span>$25.00</span>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
            <a
                href="{{ route('admin.dashboard') }}"
                style="display:block; background:#635bff; color:white; text-decoration:none; border-radius:8px; padding:12px; font-size:15px; font-weight:600;"
            >
                Back to Dashboard
            </a>
            <a
                href="{{ route('admin.stripe.test') }}"
                style="display:block; background:#f3f4f6; color:#374151; text-decoration:none; border-radius:8px; padding:12px; font-size:14px; font-weight:500;"
            >
                Make Another Payment
            </a>
        </div>
    </div>
</div>
@endsection
