<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Payment::query()->with('request')->latest('id')->get()
        );
    }

    public function store(HttpRequest $request): JsonResponse
    {
        $validated = $this->validatedData($request);

        $payment = Payment::create($validated)->load('request');

        return response()->json($payment, 201);
    }

    public function show(Payment $payment): JsonResponse
    {
        return response()->json($payment->load('request'));
    }

    public function update(HttpRequest $request, Payment $payment): JsonResponse
    {
        $validated = $this->validatedData($request, $payment);

        $payment->update($validated);

        return response()->json($payment->load('request'));
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $payment->delete();

        return response()->json(status: 204);
    }

    private function validatedData(HttpRequest $request, ?Payment $payment = null): array
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
