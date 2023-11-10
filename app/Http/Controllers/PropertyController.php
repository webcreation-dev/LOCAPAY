<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyGallery;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $main_data = $data['data'];
            $galleries = $data['gallery'];
            $main_features = $request['main_features'];
            $secondary_features = $request['secondary_features'];

            $imageName = time().'.'. $data['data']['image']->extension();
            $request->image->storeAs('images', $imageName);

            $property = Property::create($main_data);

            foreach ($galleries as $image) {

                $imageName = time().'.'. $image->extension();
                $request->image->storeAs('images', $imageName);

                $propertyImage = new PropertyGallery(['image' => $image]);
                $property->gallery()->save($propertyImage);
            }

            $property->mainFeatures()->attach($main_features);
            $property->secondaryFeatures()->attach($secondary_features);

            return self::apiResponse(true, "Location ajouté avec succès", $property);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout de la propriété");
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
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
