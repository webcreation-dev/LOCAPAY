<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * AFFICHER LES ECHEANCES
     */
    public function index()
    {
        //
    }

    /**
     * AJOUTER UNE ECHEANCE
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * AFFICHER UNE ECHEANCE
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * MODIFIER UNE ECHEANCE
     */
    public function update(Request $request, Schedule $schedule)
    {
        //
    }

    /**
     * SUPPRIMER UNE ECHEANCE
     */
    public function destroy(Schedule $schedule)
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
