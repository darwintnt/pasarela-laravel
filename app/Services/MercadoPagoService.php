<?php

namespace App\Services;

use App\Services\CurrencyConversionService;
use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class MercadoPagoService
{

    use ConsumesExternalServices;

    protected $baseUri;

    protected $accessToken;

    protected $publicKey;

    protected $baseCurrency;

    protected $currencyConverter;

    public function __construct(CurrencyConversionService $converter)
    {
        $this->baseUri = config('services.mercadopago.base_uri');
        $this->publicKey = config('services.mercadopago.public_key');
        $this->accessToken = config('services.mercadopago.access_token');
        $this->baseCurrency = config('services.mercadopago.base_currency');
        $this->currencyConverter = $converter;
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Content-Type'] = "application/json";
        $queryParams['access_token'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return $this->accessToken;
    }

    public function handlePayment(Request $request)
    {
        dd($request->all());

        $order = $this->createPayment($request->value, $request->currency, $request->paymentMethodId,
            $request->token, $request->email);

        if($order->status === 'approved') {

            $approve = $order->id;
    
            session()->put('approvalId', $approve);
    
            return redirect($approve->href);
        }

    }

    public function handleApproval()
    {
        //
    }

    public function createPayment($value, $currency, $cardNetwork, $cardToken, $email, $installments = 1)
    {
        return $this->makeRequest(
            'POST',
            '/v1/payments',
            [
                'binary_mode' => true,
                'transaction_amount' => round($value * $this->resolveFactor($currency)),
                'payment_method_id' => $cardNetwork,
                'token' => $cardToken,
                'payer' => [
                    'email' => $email,
                ],
                'installments' => $installments,
                'statement_descriptor' => config('app.name'),
            ],
            [],
            [],
            $isJsonRequest = true
        );
    }

    public function resolveFactor($currency)
    {
        return $this->currencyConverter->convertCurrency($currency, $this->baseCurrency);
    }

}
