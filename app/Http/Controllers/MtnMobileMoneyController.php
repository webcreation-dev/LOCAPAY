<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MtnMobileMoneyService;

class MtnMobileMoneyController extends Controller
{
    protected $mtnMobileMoneyService;

    /**
     * Creates a new instance of the class.
     *
     * @param MtnMobileMoneyService $mtnMobileMoneyService The MtnMobileMoneyService object.
     */
    public function __construct(MtnMobileMoneyService $mtnMobileMoneyService)
    {
        $this->mtnMobileMoneyService = $mtnMobileMoneyService;
    }

    /**
     * Initiates a payment.
     *
     * @param mixed $amount the amount of the payment
     * @param string $currency the currency of the payment
     * @param string $payerMobileNumber the mobile number of the payer
     * @return mixed the payment response
     */
    public function initiatePayment($amount, $currency, $payerMobileNumber)
    {
        // Step 1: Create API User, Create API Key, and Request to Pay
        $paymentResponse = $this->mtnMobileMoneyService->requestToPay($amount, $currency, $payerMobileNumber);

        // Handle the payment response as needed
        return response()->json($paymentResponse);
    }
}
