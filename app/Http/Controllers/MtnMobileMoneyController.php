<?php

namespace App\Http\Controllers;

use Bmatovu\MtnMomo\Products\Collection;
use GuzzleHttp\Psr7\Request;

class MtnMobileMoneyController extends Controller
{
    protected $mtnMobileMoneyService;
    protected $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    /**
     * INITIER UN PAIEMENT
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam amount numeric required Montant de la transaction.
     * @bodyParam payerMobileNumber string required Téléphone mobile de l'utilisateur (ex. : "22966877345").
     * @bodyParam reason string required Motif de la transaction.
    */
    public function initiateTransaction(Request $request)
    {
        $amount = $request->amount;
        $payerMobileNumber = $request->payerMobileNumber;
        $reason = $request->reason;
        // Request the payment from the payment collection service
        $transactionId = $this->collection->requestToPay($reason, $payerMobileNumber, $amount);
        $response = $this->collection->getTransactionStatus($transactionId);
        return self::apiResponse(true, 'Transaction effectué', $response );
    }

    public static function apiResponse($success, $message, $data = [], $status = 200) //: array
    {
        $response = response()->json([
            'success' => $success,
            'message' => $message,
            'body' => $data
        ], $status);
        return $response;
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
