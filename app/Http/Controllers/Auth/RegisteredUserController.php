<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
{
    try {
        $validatedData = $request->validate([
            'lastname' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:8'],
            'npi' => ['required', 'numeric', 'max:10'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'sexe' => ['required', 'numeric'],
            'role_id' => ['required', 'numeric'],
            'activity_id' => ['numeric'],
            'city_id' => ['numeric'],
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->storeAs('images', $imageName);

        $user = User::create([
            'lastname' => $validatedData['lastname'],
            'firstname' => $validatedData['firstname'],
            'password' => Hash::make($validatedData['password']), // Utilisez Hash::make pour hasher le mot de passe
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'npi' => $validatedData['npi'],
            'image' => $imageName,
            'sexe' => $validatedData['sexe'],
            'role_id' => $validatedData['role_id'],
            'activity_id' => $validatedData['activity_id'],
            'city_id' => $validatedData['city_id'],
        ]);

        event(new Registered($user));

        Auth::login($user);
        $token = $user->createToken('myapptoken')->plainTextToken;

        return self::apiResponse(true, "Inscription réussie", [$user, $token]);
    } catch (ValidationException $e) {
        return self::apiResponse(false, "Échec de l'inscription");
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
