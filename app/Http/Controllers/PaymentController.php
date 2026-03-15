<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        try {
            $data = Payment::with('request')->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $payment = Payment::create($validated)->load('request');

        return response()->json($payment, Response::HTTP_CREATED);
    }

    public function show(Payment $payment)
    {
        return response()->json($payment->load('request'), Response::HTTP_OK);
    }

    public function edit(Payment $payment)
    {
        //
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $this->validatedData($request, $payment);

        $payment->update($validated);

        return response()->json($payment->load('request'), Response::HTTP_OK);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    private function validatedData(Request $request, ?Payment $payment = null): array
    {
        return $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
            'transaction_reference' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('payment', 'transaction_reference')->ignore($payment?->id),
            ],
            'request_id' => ['required', 'integer', 'exists:request,id'],
        ]);
    }
}
