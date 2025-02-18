<?php

namespace App\Http\Controllers;

use App\Models\Post;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum',except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        // Création du post
        $post = $request->user()->posts()->create($validatedData);

        // Retourner une réponse JSON
        return response()->json([
            'message' => 'Post créé avec succès',
            'post' => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'message' => 'Post trouvé',
            'post' => $post
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validation des données
        $validatedData = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:255',
        ]);

        // Mise à jour du post
        $post->update($validatedData);

        // Réponse JSON
        return response()->json([
            'message' => 'Post mis à jour avec succès',
            'post' => $post
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Suppression du post
        $post->delete();
    
        // Réponse JSON
        return response()->json([
            'message' => 'Post supprimé avec succès'
        ], 200);
    }
    
}
