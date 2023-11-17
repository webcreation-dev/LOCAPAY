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

    /**
     * Initiates a payment.
     *
     * @param mixed $amount The amount of the payment.
     * @param mixed $payerMobileNumber The mobile number of the payer.
     * @param mixed $reason The reason for the payment.
     *
     * @return JsonResponse The JSON response containing the reference ID.
     */
    public function initiatePayment($amount, $payerMobileNumber, $reason)
    {
        // Request the payment from the payment collection service
        $referenceId = $this->collection->requestToPay($reason, $payerMobileNumber, $amount);

        // Return a JSON response with the reference ID
        return response()->json(['reference_id' => $referenceId]);
    }

    /**
     * Retrieves the payment status for a given reference ID.
     *
     * @param int $referenceId The reference ID of the transaction.
     * @return \Illuminate\Http\JsonResponse The JSON response containing the status of the transaction.
     */
    public function getPaymentStatus($referenceId)
    {
        $status = $this->collection->getTransactionStatus($referenceId);
        return response()->json(['status' => $status]);
    }
}
