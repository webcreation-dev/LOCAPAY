<?php

namespace App\Http\Controllers;

use App\Models\User;
use Bmatovu\MtnMomo\Products\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MtnMobileMoneyController extends Controller
{
    protected $mtnMobileMoneyService;
    protected $collection;

    public function __construct()
    {
        $this->collection = new Collection();
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
     * DEPOT ET RETRAIT
     *
     * @urlParam amount required Montant de la transaction.
     * @urlParam reason required La raison du paiement.
     * @urlParam type required Le type de transaction (Recharge ou Retrait).
     *
    */
    public function initiateTransaction($amount, $reason, $type)
    {
        $payerMobileNumber = Auth::user()->phone;
        try {
            $isUnique = User::where('phone', $payerMobileNumber)->doesntExist();

            if (!$isUnique) {
                if($type == "Recharge") {
                    $transactionId = $this->collection->requestToPay($reason, $payerMobileNumber, $amount);
                    $response = $this->collection->getTransactionStatus($transactionId);
                    return redirect()->route('action-transaction', ['response' => $response, 'transaction_id' => $transactionId, "reason" => $reason, 'type' => $type, 'amount' => $amount, 'user_id' => Auth::user()->id, 'type' => $type]);
                }else {
                    $solde = User::where('phone', $payerMobileNumber)->first()->balance;
                    if($solde < $amount) {
                        return self::apiResponse(false, "Le solde est insuffisant");
                    }else {
                        $transactionId = $this->collection->requestToPay($reason, $payerMobileNumber, $amount);
                        $response = $this->collection->getTransactionStatus($transactionId);
                        return redirect()->route('action-transaction', ['response' => $response, 'transaction_id' => $transactionId, "reason" => $reason, 'type' => $type, 'amount' => $amount, 'user_id' => Auth::user()->id, 'type' => $type]);
                    }
                }

            }else {
                return self::apiResponse(false, "Le numéro n'a pas été trouvé");
            }
        } catch (ValidationException) {
            return self::apiResponse(false, "Échec de la transaction");
        }
    }

    /**
     * TRANSFERT LOCAPAY TO LOCAPAY
     *
     * @urlParam amount required Montant de la transaction.
     * @urlParam reason required La raison du paiement.
     * @urlParam type required Le type de transaction Payment.
     * @urlParam phone required Le numéro du bénéficiaire.
     *
    */
    public function paymentTransaction($amount, $reason, $type, $phone)
    {
        try {
            $senderPhone = Auth::user()->phone;

            if($senderPhone != $phone) {
                $isUnique = User::where('phone', $phone)->doesntExist();

                if (!$isUnique) {
                        $solde = User::where('phone', $phone)->first()->balance;
                        if($solde < $amount) {
                            return self::apiResponse(false, "Le solde est insuffisant");
                        }else {
                            $transactionId = $this->collection->requestToPay($reason, $phone, $amount);
                            $response = $this->collection->getTransactionStatus($transactionId);
                            return redirect()->route('transfert-transaction', ['response' => $response, 'transaction_id' => $transactionId, "reason" => $reason, 'type' => $type, 'amount' => $amount, 'phone' => $phone, 'type' => $type]);
                        }

                }else {
                    return self::apiResponse(false, "Le numéro n'a pas été trouvé");
                }
            }else {
                return self::apiResponse(false, "Vous ne pouvez pas vous envoyer de l'argent");
            }
        } catch (ValidationException) {
            return self::apiResponse(false, "Échec de la transaction");
        }
    }

}
