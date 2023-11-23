<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * CONNECTER UN UTILISATEUR
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            $user = User::where('phone', $request->phone)->first();
            $token = $user->createToken('myapptoken')->plainTextToken;
            $user->token = $token;
            return self::apiResponse(true, "Connexion reussie", $user);
        } catch (ValidationException $e) {
            return self::apiResponse(false, "Échec de la connexion");
        } catch (AuthenticationException $e) {
            return self::apiResponse(false, "Échec de la connexion : identifiants incorrects");
        }
    }

    /**
     * DECONNECTER UN UTILISATEUR
     *
     */
    public function destroy()
    {
        auth()->user()->tokens()->delete();
        return self::apiResponse(true, "Déconnexion réussie");
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
