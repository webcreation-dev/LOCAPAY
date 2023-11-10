<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * AFFICHER LES NOTIFICATIONS
     */
    public function index()
    {
        //
    }

    /**
     * AJOUTER UNE NOTIFICATION
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * AFFICHER UNE NOTIFICATION
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * MODIFIER UNE NOTIFICATION
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * SUPPRIMER UNE NOTIFICATION
     */
    public function destroy(Notification $notification)
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
