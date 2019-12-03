<?php

namespace App\Resolvers;

use App\PaymentPlatform;

class PaymentPlatformResolver
{

    private $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function resolveService($idPlatformPayment)
    {
        $name = strtolower($this->paymentPlatforms->firstWhere('id', $idPlatformPayment)->name);

        $service = config("services.{$name}.class");

        if ($service) {
            return resolve($service);
        }

        throw new \Exception("Metodo de pago no configurado");

    }

}
