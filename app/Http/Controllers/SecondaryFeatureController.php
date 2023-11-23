<?php

namespace App\Http\Controllers;

use App\Models\SecondaryFeature;
use Illuminate\Http\Request;

class SecondaryFeatureController extends Controller
{
    /**
     * RECUPERER LES CARACTÉRISTIQUES SECONDAIRES
     */
    public function index()
    {
        $secondaryFeatures = SecondaryFeature::all();
        return self::apiResponse(true, 'Liste des caractéristiques secondaires', $secondaryFeatures);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * AJOUTER UNE CARACTÉRISTIQUE SECONDAIRE
     *
     * @bodyParam name string required Nom de la caractéristique secondaire
     */
    public function store(Request $request)
    {
        $secondaryFeature = SecondaryFeature::create($request->all());
        return self::apiResponse(true, 'Caractéristique secondaire créée avec succès', $secondaryFeature);
    }

    /**
     * Display the specified resource.
     */
    // public function show(SecondaryFeature $secondaryFeature)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(SecondaryFeature $secondaryFeature)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, SecondaryFeature $secondaryFeature)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(SecondaryFeature $secondaryFeature)
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
