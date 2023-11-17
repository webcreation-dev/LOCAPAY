<?php

namespace App\Http\Controllers;

use Bmatovu\MtnMomo\Products\Collection;

class MtnMobileMoneyController extends Controller
{
    protected $mtnMobileMoneyService;
    protected $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function initiatePayment($amount, $payerMobileNumber, $currency)
    {
        // Step 1: Create API User, Create API Key, and Request to Pay
        $referenceId = $this->collection->requestToPay('testPayment', $payerMobileNumber, $amount);

        // Handle the payment response as needed
        return response()->json(['reference_id' => $referenceId]);
    }

    public function getPaymentStatus($referenceId)
    {
        $status = $this->collection->getTransactionStatus($referenceId);
        return response()->json(['status' => $status]);
    }
}