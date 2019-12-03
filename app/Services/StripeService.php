<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Traits\ConsumesExternalServices;

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
       //
    }

    public function handleApproval()
    {
        //
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
