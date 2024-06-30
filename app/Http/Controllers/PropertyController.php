<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyGallery;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * AFFICHER LES PROPRIÉTÉS
     */
    public function index()
    {
        $properties = Property::with(['gallery', 'mainFeatures', 'secondaryFeatures'])->get();

        $properties = $properties->map(function ($property) {
            $property->main_image_url = url('storage/properties/' . $property->main_image);


            $property->gallery->each(function ($image) {
                $image->image_url = url('storage/properties/' . $image->image);
            });

            return $property;
        });

        return self::apiResponse(true, "Liste de tous les propriétés", $properties);
    }

    /**
     * LOCATIONS DU PROPRIÉTAIRE
     *
     * @bodyParam user_id numeric required ID de l'utilisateur (propriétaire).
     */
    public function getPropertiesByOwner(Request $request)
    {
        $properties = Property::byUser($request->user_id)->with(['gallery', 'mainFeatures', 'secondaryFeatures'])->get();

        // $properties = $properties->map(function ($property) {
        //     $property->main_image_url = url('storage/properties/' . $property->main_image);


        //     $property->gallery->each(function ($image) {
        //         $image->image_url = url('storage/properties/' . $image->image);
        //     });

        //     return $property;
        // });

        return self::apiResponse(true, "Liste des locations du propriétaire", $properties);
    }

    /**
     * FILTRE DES PROPRIETES PAR MOTS CLES
     *
     * @bodyParam keywords string required Nom du propriétaire.
     */
    public function searchProperty($request)
    {
        // explode the keywords saparated by comma into an array
        $keywords = explode(' ', $request->keywords);

        $properties = Property::where(function ($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->orWhere('property_last_name', 'like', '%' . $keyword . '%')
                    ->orWhere('property_first_name', 'like', '%' . $keyword . '%')
                    ->orWhere('property_location', 'like', '%' . $keyword . '%')
                    ->orWhere('description', 'like', '%' . $keyword . '%');
            }
        })->with(['gallery', 'mainFeatures', 'secondaryFeatures'])->get();

        return $properties;
    }

    /**
        * AJOUTER UNE PROPRIÉTÉ
        *
        * @bodyParam property_last_name string required Nom du propriétaire.
        * @bodyParam property_first_name string required Prénom du propriétaire.
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
        // try {

            $data = $request->validate([
                'property_last_name' => ['required', 'string', 'max:255'],
                'property_first_name' => ['required', 'string', 'max:255'],
                'property_location' => ['required', 'string'],
                'monthly_rent' => ['required', 'numeric'],
                'owner_phone' => ['required', 'numeric'],
                'description' => ['required', 'string'],
                'main_image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg'],
                'status' => ['numeric'],
                'rating' => ['numeric'],
                'general_rating' => ['numeric'],
                'team_rating' => ['numeric'],
                'city_id' => ['required', 'numeric'],
            ]);

            $data['user_id'] = Auth::user()->id;

            $galleries = $request['galleries'];
            $main_features = $request['main_features'];
            $secondary_features = $request['secondary_features'];

            $image = $request->main_image;
            // $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imageName = str_replace(' ', '-', $image->getClientOriginalName());
            $image->storeAs('properties', $imageName, 'public');

            $data['main_image'] = $imageName;

            $property = Property::create($data);

            foreach ($galleries as $galleryImage) {

                $galleryImageName = str_replace(' ', '-', $galleryImage->getClientOriginalName());
                // $galleryImageName = time() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->storeAs('properties', $galleryImageName, 'public');


                $propertyImage = new PropertyGallery(['image' => $galleryImageName]);
                $property->gallery()->save($propertyImage);
            }

            $property->mainFeatures()->attach($main_features);
            $property->secondaryFeatures()->attach($secondary_features);

            return self::apiResponse(true, "Location ajouté avec succès", $property);
        // }catch( ValidationException $e) {
        //     $errors = $e->errors();

        //     return self::apiResponse(false, "Échec de l'ajout de la propriété", $errors);
        // }
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
