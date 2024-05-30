<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredController extends Controller
{
    /**
         * ENREGISTRER UN NOUVEL UTILISATEUR
         *
        //  * @bodyParam lastname string required Nom de famille de l'utilisateur.
        //  * @bodyParam firstname string required Prénom de l'utilisateur.
         * @bodyParam password string required Mot de passe de l'utilisateur.
         * @bodyParam password_confirmation string required Confirmation du mot de passe (doit correspondre au mot de passe).
        //  * @bodyParam email string required Adresse e-mail de l'utilisateur.
         * @bodyParam phone string required Numéro de téléphone de l'utilisateur.
        //  * @bodyParam npi numeric required Numéro NPI de l'utilisateur.
        //  * @bodyParam image file required Image de profil de l'utilisateur (formats autorisés : jpeg, png, jpg, gif, svg ; taille maximale : 2 Mo).
        //  * @bodyParam sexe numeric required Sexe de l'utilisateur (par exemple, 1 pour masculin, 2 pour féminin).
        //  * @bodyParam role_id numeric required ID du rôle de l'utilisateur.
        //  * @bodyParam activity_id numeric ID de l'activité de l'utilisateur (facultatif).
        //  * @bodyParam city_id numeric ID de la ville de l'utilisateur (facultatif).
    **/
    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                    // 'lastname' => ['required', 'string', 'max:255'],
                    // 'firstname' => ['required', 'string', 'max:255'],
                    'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
                    // 'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'phone' => ['required', 'string', 'max:8', 'unique:users'],
                    // 'npi' => ['required', 'numeric', 'unique:users'],
                    // 'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg'],
                    // 'sexe' => ['required', 'numeric'],
                    'role_id' => ['required', 'numeric'],
                    // 'activity_id' => ['numeric'],
                    // 'city_id' => ['numeric'],
            ]);

            $imageName = null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = str_replace(' ', '-', $image->getClientOriginalName());
                $image->storeAs('images', $imageName, 'public');
            }

            $user = User::create([
                // 'lastname' => $validatedData['lastname'],
                // 'firstname' => $validatedData['firstname'],
                'password' => Hash::make($validatedData['password']),
                // 'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                // 'npi' => $validatedData['npi'],
                // 'image' => $imageName,
                // 'sexe' => $validatedData['sexe'],
                'role_id' => $validatedData['role_id'],
                // 'activity_id' => $validatedData['activity_id'] ?? null,
                // 'city_id' => $validatedData['city_id'] ?? null,
            ]);

            event(new Registered($user));

            Auth::login($user);
            $token = $user->createToken('myapptoken')->plainTextToken;
            $userResponse = User::find($user->id);
            $userResponse->token = $token;

            return self::apiResponse(true, "Inscription réussie", $userResponse);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return self::apiResponse(false, "Échec de l'inscription", $errors);
        }
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
