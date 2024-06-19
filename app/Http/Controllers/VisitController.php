<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class VisitController extends Controller
{
    /**
     * LISTE DES VISITES
     */
    public function index()
    {
        $visits = Visit::where('user_id', Auth::user()->id)->get()->map(function ($visit) {

            $visit->manager = $visit->manager;
            $visit->properties = $visit->visitProperties->map(function ($visitProperty) {

                $property = $visitProperty->property;

                $property->main_image_url = url('storage/properties/' . $property->main_image);
                $property->gallery->each(function ($image) {
                    $image->image_url = url('storage/properties/' . $image->image);
                });

                return $property;
            });
            $visit->makeHidden('visitProperties');
            return $visit;
        });

        return self::apiResponse(true, "Liste des visites de l'utilisateur", $visits);
    }


    /**
     * ENREGISTRER UNE VISITE
     *
     * @bodyParam property_id numeric required ID de la propriété (propriétaire).
     */
    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'property_id' => ['required', 'numeric'],
            ]);

            $property = Property::find($data['property_id']);
            $visit = Visit::where('manager_id',$property->user_id)->where('user_id', Auth::user()->id)->first();

            if ($visit) {

                $visit->visitProperties()->create([
                    'property_id' => $data['property_id']
                ]);
            }else {

                $data['user_id'] = Auth::user()->id;
                $data['manager_id'] = $property->user_id;
                $visit = Visit::create($data);

                $visit->visitProperties()->create([
                    'property_id' => $data['property_id']
                ]);
            }

            return self::apiResponse(true, "Visite ajoutée avec succès", $visit);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'ajout de la visite", $errors);
        }
    }

    /**
     * RECUPERER UNE VISITE
     *
     * @urlParam visit Paramètre d'URL obligatoire. ID de la visite à afficher.
     */
    public function show(Visit $visit)
    {

        $visit->manager = $visit->manager;
        $visit->properties = $visit->visitProperties->map(function ($visitProperty) {

            $property = $visitProperty->property;

            $property->main_image_url = url('storage/properties/' . $property->main_image);
            $property->gallery->each(function ($image) {
                $image->image_url = url('storage/properties/' . $image->image);
            });

            return $property;
        });

        return self::apiResponse(true, "Détails de la visite", $visit);
    }

    /**
     * METTRE A JOUR UNE VISITE
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * SUPPRIMER UNE VISITE
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
