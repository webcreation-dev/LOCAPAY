<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    /**
     * AFFICHER LES NOTIFICATIONS D'UN UTILISATEUR
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $notifications = Notification::where('user_id', $user_id)->get();
        return self::apiResponse(true, "Liste des notifications de l'utilisateur", $notifications);
    }


    /**
     * AJOUTER UNE NOTIFICATION
     *
     * @bodyParam user_id numeric required ID de l'utilisateur lié à la notification.
     * @bodyParam message string required Message de la notification.
     * @bodyParam status enum required Statut de la notification (View, Not View).
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $notification = Notification::create($data);
            return self::apiResponse(true, "Notification ajoutée avec succès", $notification);
        }catch( ValidationException ) {
            return self::apiResponse(false, "Échec de l'ajout de la notification");
        }
    }

    /**
     * AFFICHER UNE NOTIFICATION
     *
     * @urlParam notification Paramètre d'URL obligatoire. ID de la notification à afficher.
     */
    public function show(Notification $notification)
    {
        return self::apiResponse(true, "Notification trouvée", $notification);
    }

    /**
     * MODIFIER UNE NOTIFICATION
     */
    // public function update(Request $request, Notification $notification)
    // {
    //     //
    // }

    /**
     * SUPPRIMER UNE NOTIFICATION
     */
    // public function destroy(Notification $notification)
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
