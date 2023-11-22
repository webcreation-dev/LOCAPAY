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
        $properties = Property::all();
        return self::apiResponse(true, "Liste de tous les propriétés", $properties);
    }

    /**
        * AJOUTER UNE PROPRIÉTÉ
        *
        * @bodyParam property_lastname string required Nom du propriétaire.
        * @bodyParam property_firstname string required Prénom du propriétaire.
        * @bodyParam property_location string required Emplacement de la propriété.
        * @bodyParam monthly_rent numeric required Loyer mensuel de la propriété.
        * @bodyParam description string required Description de la propriété.
        * @bodyParam main_image file required Image principale de la propriété.
        * @bodyParam owner_phone numeric required Numéro de téléphone du propriétaire.
        * @bodyParam status numeric required Statut de la propriété.
        * @bodyParam rating numeric required Évaluation de la propriété.
        * @bodyParam general_rating numeric required Évaluation générale de la propriété.
        * @bodyParam team_rating numeric required Évaluation de l'équipe associée à la propriété.
        * @bodyParam user_id numeric required ID de l'utilisateur (propriétaire).
        * @bodyParam city_id numeric required ID de la ville où se trouve la propriété.
        * @bodyParam galleries array required Tableau des images de la propriété.
        * @bodyParam main_features array required Tableau des caractéristiques principales de la propriété.
        * @bodyParam secondary_features array Tableau des caractéristiques secondaires de la propriété.
        *
    */
    public function store(Request $request)
    {
        try {

            $data = $request->all();

            $galleries = $data['galleries'];
            $main_features = $request['main_features'];
            $secondary_features = $request['secondary_features'];

            $imageName = time().'.'. $data['main_image']->extension();
            $request->image->storeAs('images', $imageName);

            $property = Property::create($data);

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
     *
     * @urlParam property Paramètre d'URL obligatoire. ID de la propriété à afficher.
     */
    public function show(Property $property)
    {
        return self::apiResponse(true, "Propriété trouvée", $property);
    }

    /**
     * MODIFIER UNE PROPRIÉTÉ
     */
    // public function update(Request $request, Property $property)
    // {
    //     //
    // }

    /**
     * SUPPRIMER UNE PROPRIÉTÉ
     */
    // public function destroy(Property $property)
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
