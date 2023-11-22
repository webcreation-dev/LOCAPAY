<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * RECUPERER LES ACTIVITES
     */
    public function index()
    {
        $activities = Activity::all();
        return self::apiResponse(true, 'Liste des activités', $activities);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    // public function create()
    // {

    // }

    /**
     * CREER UNE ACTIVITE
     *
     * @bodyParam name string required Nom de l'activité
     *
     */
    public function store(Request $request)
    {
        $activity = Activity::create($request->all());
        return self::apiResponse(true, 'Activité créée avec succès', $activity);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Activity $activity)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Activity $activity)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Activity $activity)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(Activity $activity)
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
