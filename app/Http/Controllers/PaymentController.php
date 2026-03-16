<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    // Display all payments
    public function index()
    {
        try {
            $data = Payment::all();
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Show the form for creating a new payment
    public function create()
    {
        //
    }

    // Store a newly created payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount',
            'payment_method',
            'status',
            'transaction_reference',
            'request_id',
        ]);

        $payment = Payment::create($validated);

        return response()->json($payment, Response::HTTP_CREATED);
    }

    // Display a specific payment
    public function show(Payment $payment)
    {
        return response()->json($payment, Response::HTTP_OK);
    }

    // Show the form for editing a payment
    public function edit(Payment $payment)
    {
        //
    }

    // Update a payment
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount',
            'payment_method',
            'status',
            'transaction_reference',
            'request_id',
        ]);

        $payment->update($validated);

        return response()->json($payment, Response::HTTP_OK);
    }

    // Delete a payment
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
