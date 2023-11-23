<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ScheduleController extends Controller
{
    /**
     * AFFICHER LES ECHEANCES
     */
    public function index()
    {
        $schedules = Schedule::all();
        return self::apiResponse(true, "Liste de toutes les échéances", $schedules);
    }

    /**
     * AJOUTER UNE ECHEANCE
     *
     * @bodyParam contract_id int required The id of the contract. Example: 1
     * @bodyParam transaction_id int required The id of the transaction. Example: 1
     * @bodyParam type enum required Type de transaction (Rent ou Advance).
     * @bodyParam month_of_rent date required The month of the rent. Example: 1
     * @bodyParam amount_to_pay int required The amount to pay. Example: 100000
     * @bodyParam amount_paid int required The amount paid. Example: 0
     * @bodyParam remaining_to_pay int required The remaining to pay. Example: 100000
     * @bodyParam status enum required Status of the schedule (Unpaid or Paid).
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'contract_id' => ['required', 'numeric'],
                'transaction_id' => ['required', 'numeric'],
                'month_of_rent' => ['required', 'date'],
                'amount_to_pay' => ['required', 'numeric'],
                'amount_paid' => ['required', 'numeric'],
                'remaining_to_pay' => ['required', 'numeric'],
                'type' => ['required', 'in:Rent,Advance'],
                'status' => ['required', 'in:Paid,Unpaid'],
            ]);
            $schedule = Schedule::create($data);

            return self::apiResponse(true, "Echéance ajoutée avec succès", $schedule);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'ajout de l'échéance", $errors);
        }
    }

    /**
     * AFFICHER UNE ECHEANCE
     *
     * @urlParam schedule required ID de l'échéance.
     */
    public function show(Schedule $schedule)
    {
        return self::apiResponse(true, "Détails de l'échéance", $schedule);
    }

    /**
     * MODIFIER UNE ECHEANCE
     */
    // public function update(Request $request, Schedule $schedule)
    // {
    //     //
    // }

    /**
     * SUPPRIMER UNE ECHEANCE
     */
    // public function destroy(Schedule $schedule)
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
