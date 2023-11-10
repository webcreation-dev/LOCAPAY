<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyGallery;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * AFFICHER LES PROPRIÉTÉS
     */
    public function index()
    {
        //
    }

    /**
     * AJOUTER UNE PROPRIÉTÉ
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
     * AFFICHER UNE PROPRIÉTÉ
     */
    public function show(Property $property)
    {
        //
    }

    /**
     * MODIFIER UNE PROPRIÉTÉ
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * SUPPRIMER UNE PROPRIÉTÉ
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
