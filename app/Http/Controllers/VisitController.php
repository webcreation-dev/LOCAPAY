<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $visits = Visit::where('user_id', Auth::user()->id )->get();
        return self::apiResponse(true, "Liste des visites de l'utilisateur", $visits);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'property_id' => ['required', 'numeric'],
                'visit_date' => ['required', 'date'],
                'price' => ['required', 'numeric'],
            ]);

            $data['user_id'] = Auth::user()->id;
            $visit = Visit::create($data);

            return self::apiResponse(true, "Visite ajoutée avec succès", $visit);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'ajout de la visite", $errors);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Visit $visit)
    {
        return self::apiResponse(true, "Détails de la visite", $visit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visit $visit)
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
