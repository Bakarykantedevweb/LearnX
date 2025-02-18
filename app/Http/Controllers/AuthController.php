<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        // Création du post
        $user = User::create($validatedData);

        $token = $user->createToken($request->name);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        $user = User::where('email', $validatedData['email'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) 
        {
            return response()->json([
                'message' => 'Email ou mot de passe incorrect',
            ], 401); 
        }

        $token = $user->createToken($user->name);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Connexion réussie',
            'user' => $user,
            'token' => $token->plainTextToken,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
          // Retourner une réponse JSON
          return response()->json([
            'message' => 'Merci pour votre visite',
        ], 201);
    }
}
