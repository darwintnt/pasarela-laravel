<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class StripeService
{

    use ConsumesExternalServices;

    protected $baseUri;

    protected $secretKey;

    protected $publicKey;

    public function __construct()
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->publicKey = config('services.stripe.public_key');
        $this->secretKey = config('services.stripe.secret_key');
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return "Bearer {$this->secretKey}";
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        $intent = $this->createIntent($request->value, $request->currency, $request->payment_method);

        session()->put('paymentIntentId', $intent->id);

        return redirect()->route('approval');
    }

    public function handleApproval()
    {
        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');
            $payment = $this->confirmPayment($paymentIntentId);

            if ($payment->status === 'requires_action') {
                $clientSecret = $payment->client_secret;

                return view('stripe.3d-secure')->with([
                    'clientSecret' => $clientSecret
                ]);
            }

            if ($payment->status === 'succeeded') {
                $name = $payment->charges->data[0]->billing_details->name;
                $currency = strtoupper($payment->currency);
                $amount = $payment->amount / $this->resolveFactor($currency);

                return redirect('home')->withSuccess(['payment' => "Gracias!! {$name}, hemos recibido tu pago por {$amount} {$currency}"]);
            }
        }

        return redirect('home')->withErrors('No ha sido posible confirmar el pago, intente nuevamente mas tarde');
    }

    public function createIntent($value, $currency, $paymentMethod)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => strtolower($currency),
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',
            ]
        );

    }

    public function confirmPayment($paymentIntent)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntent}/confirm",
            [],
            [
                // 'return_url' => route('approval'),
                // 'receipt_email' => Auth()->user()->email
            ]
        );

    }

    public function resolveFactor($currency)
    {
        $zeroDecimals = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimals)) {
            return 1;
        }

        return 100;
    }

}
