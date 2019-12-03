<?php

namespace App\Http\Controllers;

use App\Resolvers\PaymentPlatformResolver;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    private $platform;

    public function __construct(PaymentPlatformResolver $platform)
    {
        $this->middleware('auth');
        $this->platform = $platform;
    }

    public function pay(Request $request)
    {
        $rules = [
            'value' => 'required|numeric|min:5',
            'currency' => 'required|exists:currencies,iso',
            'payment_platform' => 'required|exists:payment_platforms,id',
        ];

        $request->validate($rules);

        $paymentPlatform = $this->platform->resolveService($request->payment_platform);

        session()->put('platform_id', $request->payment_platform);

        return $paymentPlatform->handlePayment($request);

    }

    public function approval()
    {
        if (session()->has('platform_id')) {

            $paymentPlatform = $this->platform->resolveService(session()->get('platform_id'));

            return $paymentPlatform->handleApproval();
        }

        return redirect()->route('home')->withErrors('No es posible acceder a la plataforma de pago');
    }

    public function cancelled()
    {
        return redirect('home')->withErrors('El pago ha sido cancelado');
    }
}
