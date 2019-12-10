<?php

namespace App\Services;

use App\Services\CurrencyConversionService;
use App\Traits\ConsumesExternalServices;
use Illuminate\Http\Request;

class EPaycoService
{

    use ConsumesExternalServices;

    protected $baseUri;

    protected $publicKey;

    protected $privateKey;

    protected $baseCurrency;

    protected $currencyConverter;

    public function __construct(CurrencyConversionService $converter)
    {
        $this->baseUri = config('services.epayco.base_uri');
        $this->publicKey = config('services.epayco.public_key');
        $this->privateKey = config('services.epayco.private_key');
        $this->baseCurrency = config('services.epayco.base_currency');
        $this->currencyConverter = $converter;
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        // $headers['Content-Type'] = "application/json; charset=utf-8";
        // $queryParams['access_token'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        // return $this->accessToken;
    }

    public function handlePayment(Request $request)
    {

        $request->validate([
            "value" => "required",
            "currency" => "required",
            "epaycoToken" => "required",
        ]);

        $order = $this->createPayment(
            $request->value,
            $request->currency,
            $request->epaycoToken
        );

        if ($order->success) {

            $name = $order->data->nombres;
            $currency = strtoupper($order->data->moneda);
            $amount = number_format($order->data->valor, 0, ',', '.');

            $originalAmount = $request->value;
            $originalCurrency = strtoupper($request->currency);

            return redirect('home')
                ->withSuccess(['payment' => "Gracias!! {$name}, hemos recibido tu pago por {$originalAmount} {$originalCurrency} ({$amount} {$currency})"]);
        }

        return redirect('home')->withErrors('No ha sido posible realizar el pago, intente nuevamente mas tarde');

    }

    public function handleApproval()
    {
        //
    }

    public function createPayment($value, $currency, $cardToken, $installments = 1)
    {
        return $this->makeRequest(
            'POST',
            '/restpagos/pagos',
            [],
            [
                'public_key' => $this->publicKey,
                'token_card' => $cardToken,
                'cuotas' => $installments,
                'valor' => round($value * $this->resolveFactor($currency)),
                'url_respuesta' => route('approval'),
                'url_confirmacion' => route('cancelled'),
                'moneda' => strtoupper($this->baseCurrency),
                'enpruebas' => true
            ],
            [],
            $isJsonRequest = true
        );
    }

    public function resolveFactor($currency)
    {
        return $this->currencyConverter->convertCurrency($currency, $this->baseCurrency);
    }

}
