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
            $visit->makeHidden('visitProperties', 'user_id', 'manager_id', 'created_at', 'updated_at');
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



            $visitProperty = Visit::where('user_id', Auth::user()->id)->whereHas('visitProperties', function ($query) use ($data) {
                $query->where('property_id', $data['property_id']);
            })->whereIn('status', ['waiting', 'in_progress'])->first();

            if ($visitProperty) {
                return self::apiResponse(false, "Vous avez déjà ajouté cette propriété à une visite en attente ou en cours");
            }

            $property = Property::find($data['property_id']);
            $visit = Visit::where('manager_id',$property->user_id)->where('user_id', Auth::user()->id)->first();

            if ($visit) {

                $visit->visitProperties()->create([
                    'property_id' => $data['property_id']
                ]);
            }else {

                $data['user_id'] = Auth::user()->id;
                $data['manager_id'] = $property->user_id;
                $data['code'] = 'VISIT-' . time();
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
     *
     * @urlParam visit Paramètre d'URL obligatoire. ID de la visite à afficher.
     * @bodyParam status string required Statut de la visite
     * @bodyParam visit_date date required Date de la visite
     */
    public function update(Request $request, Visit $visit)
    {
        $visit->update($request->all());
        return self::apiResponse(true, "Visite mise à jour avec succès", $visit);
    }

    /**
     * SUPPRIMER UNE VISITE
     *
     * @urlParam visit Paramètre d'URL obligatoire. ID de la visite à afficher.
     */
    public function destroy(Visit $visit)
    {
        $visit->delete();
        return self::apiResponse(true, "Visite supprimée avec succès");
    }


    /**
     * SUPPRIMER UNE PROPRIETE D'UNE VISITE
     *
     * @urlParam visit Paramètre d'URL obligatoire. ID de la visite à afficher.
     * @urlParam property Paramètre d'URL obligatoire. ID de la propriété à supprimer.
     */
    public function deleteProperty(Visit $visit, Property $property)
    {
        $visitProperty = $visit->visitProperties()->where('property_id', $property->id)->first();

        if($visit->visitProperties()->count() == 1){
            $visit->delete();
            return self::apiResponse(true, " Dernière propriété et visite supprimée avec succès");
        }

        $visitProperty->delete();
        return self::apiResponse(true, "Propriété supprimée de la visite avec succès");
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
