<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * AFFICHER LES TRANSACTIONS
     */
    public function index()
    {
        //
    }

    /**
     * AJOUTER UNE TRANSACTION
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * AFFICHER UNE TRANSACTION
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * MODIFIER UNE TRANSACTION
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * SUPPRIMER UNE TRANSACTION
     */
    public function destroy(Transaction $transaction)
    {
        //
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
}
