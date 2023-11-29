<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    /**
     * AFFICHER LES TRANSACTIONS
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user_id)->get();
        return self::apiResponse(true, "Liste de toutes les transactions", $transactions);
    }

    /**
     * AJOUTER UNE TRANSACTION
     *
     * @bodyParam contract_id numeric required ID du contrat lié à la transaction.
     * @bodyParam user_id numeric required ID de l'utilisateur lié à la transaction.
     * @bodyParam amount numeric required Montant de la transaction.
     * @bodyParam type enum required Type de transaction (Recharge ou Payment).
     * @bodyParam transaction_id string required ID de la transaction.
     * @bodyParam reason string required Motif de la transaction.
     *
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'contract_id' => ['required', 'numeric'],
                'user_id' => ['required', 'numeric'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:Recharge,Payment'],
                'transaction_id' => ['required', 'string'],
                'reason' => ['required', 'string'],
            ]);

            $transaction = Transaction::create($data);

            return self::apiResponse(true, "Transaction ajoutée avec succès", $transaction);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'ajout de la transaction", $errors);
        }
    }

    public function actionTransaction(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => ['required', 'numeric'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:Recharge,Retrait'],
                'transaction_id' => ['required', 'string'],
                'reason' => ['required', 'string'],
            ]);

            $user = User::find($request->user_id);

            if($request->type == "Recharge") {
                $user->balance = $user->balance + $request->amount;
                $user->save();
            }else {
                $user->balance = $user->balance - $request->amount;
                $user->save();
            }

            $transaction = Transaction::create($data);
            $transaction->balance = $user->balance;
            return self::apiResponse(true, "Transaction ajoutée avec succès", $transaction);

        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de la transaction", $errors);
        }
    }

    public function transfertTransaction(Request $request)
    {
        try {
            $data = $request->validate([
                'phone' => ['required', 'numeric'],
                'amount' => ['required', 'numeric'],
                'type' => ['required'],
                'transaction_id' => ['required', 'string'],
                'reason' => ['required', 'string'],
            ]);

            $user = User::byPhone($request->phone);
            $user->balance = $user->balance + $request->amount;

            $transaction = Transaction::create($data);
            $transaction->somme = $request->amount;
            return self::apiResponse(true, "Transfert validé avec succès", $transaction);

        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec du transfert", $errors);
        }
    }


    /**
     * PAIEMENT D'UN CONTRAT
     *
     * @bodyParam contract_id numeric required ID du contrat lié à la transaction.
     * @bodyParam user_id numeric required ID de l'utilisateur lié à la transaction.
     * @bodyParam amount numeric required Montant de la transaction.
     * @bodyParam type enum required Type de transaction (Momo ou Locapay).
     * @bodyParam transaction_id string required ID de la transaction.
     * @bodyParam reason string required Motif de la transaction.
     *
     */
    public function paymentContract(Request $request)
    {
        try {
            $data = $request->validate([
                'contract_id' => ['required', 'numeric'],
                'user_id' => ['required', 'numeric'],
                'amount' => ['required', 'numeric'],
                'type' => ['required', 'in:Recharge,Payment'],
                'transaction_id' => ['required', 'string'],
                'reason' => ['required', 'string'],
            ]);

            $transaction = Transaction::create($data);

            return self::apiResponse(true, "Transaction ajoutée avec succès", $transaction);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'ajout de la transaction", $errors);
        }
    }


    /**
     * AFFICHER UNE TRANSACTION
     *
     * @urlParam transaction required ID de la transaction.
     */
    public function show(Transaction $transaction)
    {
        return self::apiResponse(true, "Détails de la transaction", $transaction);
    }

    /**
     * MODIFIER UNE TRANSACTION
     */
    // public function update(Request $request, Transaction $transaction)
    // {
    //     //
    // }

    /**
     * SUPPRIMER UNE TRANSACTION
     */
    // public function destroy(Transaction $transaction)
    // {
    //     //
    // }

    public static function apiResponse($success, $message, $data = [], $status = 200) //: array
    {
        $response = response()->json([
            'success' => $success,
            'message' => $message,
            'body' => $data
        ], $status);
        return $response;
    }
}
