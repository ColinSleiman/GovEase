<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'transaction_reference' => ['required', 'string', 'max:255', 'unique:payments,transaction_reference'],
            'request_id' => ['required', 'exists:requests,id'],
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
            'amount' => ['sometimes', 'required', 'numeric', 'min:0'],
            'payment_method' => ['sometimes', 'required', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'string', 'max:255'],
            'transaction_reference' => ['sometimes', 'required', 'string', 'max:255', 'unique:payments,transaction_reference,' . $payment->id],
            'request_id' => ['sometimes', 'required', 'exists:requests,id'],
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
