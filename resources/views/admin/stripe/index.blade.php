@extends('layouts.admin')

@section('title', 'Billing | GovEase Admin')
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
@section('content')
<div class="admin-page">
    <section class="card-padded">
        <h1 class="admin-page-title">Billing</h1>
        <p class="admin-page-subtitle">Complete your payment securely below.</p>
    </section>

    <div class="card-padded" style="max-width:480px;">
        <div class="card-header" style="margin-bottom:20px;">
            <div>
                <h2 class="card-title">Order Summary</h2>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280;">
                <span>Document Request Fee</span>
                <span>$20.00</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280;">
                <span>Processing Fee</span>
                <span>$5.00</span>
            </div>
            <hr style="border:none; border-top:1px solid #e5e7eb; margin:4px 0;">
            <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#111827;">
                <span>Total</span>
                <span id="total-amount" data-amount="2500">$25.00</span>
            </div>
        </div>

        <form id="payment-form" method="POST" id="stripe-form">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="font-size:12px; font-weight:600; color:#6b7280; display:block; margin-bottom:6px;">CARDHOLDER NAME</label>
                <input
                    type="text"
                    id="cardholder-name"
                    placeholder="Jane Doe"
                    style="width:100%; border:1px solid #d1d5db; border-radius:8px; padding:10px 14px; font-size:14px; box-sizing:border-box;"
                />
            </div>
            <input type="hidden" name="price" value="20">

            <div>
                <label style="font-size:12px; font-weight:600; color:#6b7280; display:block; margin-bottom:6px;">CARD DETAILS</label>
                <div id="card-element" style="border:1px solid #d1d5db; border-radius:8px; padding:12px 14px; background:#ffffff; box-shadow:0 1px 3px rgba(0,0,0,0.06);"></div>
                <div id="card-errors" style="color:#dc2626; font-size:13px; margin-top:6px;"></div>
            </div>
            <input type="submit"></input>
        </form>
    </div>

    {{-- Payment Link Card --}}
    <div class="card-padded" style="max-width:480px; margin-top:20px;">
        <div class="card-header" style="margin-bottom:20px;">
            <div>
                <h2 class="card-title">Order Summary</h2>
                <p class="card-subtitle">Powered by Stripe Payment Link</p>
            </div>
            <span style="background:#ede9fe; color:#6d28d9; border-radius:6px; padding:4px 10px; font-size:11px; font-weight:700; letter-spacing:.5px;">HOSTED BY STRIPE</span>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px; margin-bottom:24px;">
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280;">
                <span>Document Request Fee</span>
                <span>$5</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:14px; color:#6b7280;">
                <span>Processing Fee</span>
                <span>$0.00</span>
            </div>
            <hr style="border:none; border-top:1px solid #e5e7eb; margin:4px 0;">
            <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#111827;">
                <span>Total</span>
                <span>$5.00</span>
            </div>
        </div>

        <a
            href="https://buy.stripe.com/test_bJe4gy2DI3Go4JVd2T0VO00"
            style="display:flex; align-items:center; justify-content:center; gap:10px; width:100%; background:#635bff; color:white; text-decoration:none; border-radius:8px; padding:13px; font-size:15px; font-weight:600; box-sizing:border-box; transition:background .2s;"
            onmouseover="this.style.background='#4f46e5'"
            onmouseout="this.style.background='#635bff'"
        >
            <svg width="18" height="18" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Pay $25.00 with Stripe
        </a>

        <p style="text-align:center; font-size:12px; color:#9ca3af; margin-top:12px;">
            You will be redirected to Stripe's secure checkout page.
        </p>
    </div>
</div>

<script src="https://js.stripe.com/dahlia/stripe.js"></script>
<script type="text/javascript">
    var stripe = Stripe('{{ env("STRIPE_KEY") }}');
var elements = stripe.elements();
var cardElement = elements.create("card");
cardElement.mount('#card-element');

const createToken = async (event) => {
    event.preventDefault();

    // Read the amount in cents from the data attribute (set server-side)
    const amount = document.getElementById('total-amount').dataset.amount;

    // Step 1: Ask your backend to create a PaymentIntent
    const response = await fetch("{{ route('admin.stripe.create-intent') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ amount }),
    });

    const { clientSecret } = await response.json();

    // Step 2: Confirm the payment on the frontend using the card element
    const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
        payment_method: {
            card: cardElement,
            billing_details: {
                name: document.getElementById('cardholder-name').value
            }
        }
    });

    if (error) {
        console.error('Payment failed:', error.message);
    } else if (paymentIntent.status === 'succeeded') {
        console.log('Payment succeeded!', paymentIntent);
        // redirect or show success here
    }
};

document.getElementById('payment-form').addEventListener('submit', createToken);

</script>
</html>
@endsection
