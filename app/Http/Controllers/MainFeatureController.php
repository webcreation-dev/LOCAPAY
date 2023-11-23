<?php

namespace App\Http\Controllers;

use App\Models\MainFeature;
use Illuminate\Http\Request;

class MainFeatureController extends Controller
{
    /**
     * RECUPERER LES CARACTÉRISTIQUES PRINCIPALES
     */
    public function index()
    {
        $mainFeatures = MainFeature::all();
        return self::apiResponse(true, 'Liste des caractéristiques principales', $mainFeatures);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * AJOUTER UNE CARACTÉRISTIQUE PRINCIPALE
     *
     * @bodyParam name string required Nom de la caractéristique principale
     */
    public function store(Request $request)
    {
        $mainFeature = MainFeature::create($request->all());
        return self::apiResponse(true, 'Caractéristique principale créée avec succès', $mainFeature);
    }

    /**
     * Display the specified resource.
     */
    // public function show(MainFeature $mainFeature)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(MainFeature $mainFeature)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, MainFeature $mainFeature)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(MainFeature $mainFeature)
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
