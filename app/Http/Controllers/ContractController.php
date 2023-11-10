<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContractController extends Controller
{
    /**
     *  AFFICHER LES CONTRATS
     */
    public function index()
    {
        //
    }

    /**
     * AJOUTER UN CONTRAT
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $document = time() . '.' . $request->file('document')->extension();
            $request->file('document')->storeAs('contracts', $document);

            $contract = Contract::create($data);

            return self::apiResponse(true, "Contrat ajouté avec succès", $contract);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout du contrat");
        }
    }

    /**
     * AFFICHER UN CONTRAT
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * MODIFIER UN CONTRAT
     */
    public function update(Request $request, Contract $contract)
    {
        //
    }

    /**
     * SUPPRIMER UN CONTRAT
     */
    public function destroy(Contract $contract)
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
