<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * RECUPERER LES VILLES
     */
    public function index()
    {
        $cities = City::all();
        return self::apiResponse(true, 'Liste des villes', $cities);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * CREER UNE VILLE
     *
     * @bodyParam name string required Nom de la ville
     */
    public function store(Request $request)
    {
        $city = City::create($request->all());
        return self::apiResponse(true, 'Ville créée avec succès', $city);
    }

    /**
     * Display the specified resource.
     */
    // public function show(City $city)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(City $city)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, City $city)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(City $city)
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
