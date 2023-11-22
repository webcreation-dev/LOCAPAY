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
        $contracts = Contract::all();
        return self::apiResponse(true, "Liste de tous les contrats", $contracts);
    }

    /**
     * AJOUTER UN CONTRAT
     *
     * @bodyParam property_id numeric required ID de la propriété liée au contrat.
     * @bodyParam beneficiary_id numeric required ID du locataire lié au contrat.
     * @bodyParam landlord_id numeric required ID du propriétaire lié au contrat.
     * @bodyParam amount numeric required Montant de l'avance.
     * @bodyParam start_date date required Date de début du contrat (au format Y-m-d).
     * @bodyParam type enum required Type de contrat (Service ou Location).
     * @bodyParam document file required Document du contrat (formats autorisés : pdf, doc, docx ; taille maximale : 5 Mo).
     * @bodyParam observations string required Observations
     * @bodyParam status enum required Statut du contrat (Pending, Active, Terminated).

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
     *
     * @urlParam contract Paramètre d'URL obligatoire. ID du contrat à afficher.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * MODIFIER UN CONTRAT
     */
    // public function update(Request $request, Contract $contract)
    // {
    //     //
    // }

    /**
     * SUPPRIMER UN CONTRAT
     */
    // public function destroy(Contract $contract)
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
