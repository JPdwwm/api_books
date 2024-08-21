<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
                // On récupère tous les utilisateurs
                $authors = Author::all();

                // On retourne les informations des utilisateurs en format JSON
                return response()->json($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048'
        ]);

        $photoName = null; // Initialiser la variable

        // Gestion de l'upload d'image
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            if ($photo->isValid()) {
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads'), $photoName);
            } else {
                return response()->json(['error' => 'Erreur lors du téléchargement de la photo.'], 400);
            }
        }

        // On créé un nouvel auteur
        $author = Author::create(array_merge(
            $request->only(['first_name', 'last_name']),
            ['photo' => $photoName] // Ajoutez le nom de la photo uniquement si elle est définie
        ));

        // On retourne les informations du nouvel auteur en format JSON
        return response()->json([
            'status' => 'Success',
            'data' => $author,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return response()->json($author);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            
        ]);

        // Gestion de l'upload d'image
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            if ($photo->isValid()) {
                $photoName = time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('uploads'), $photoName);
                $request->merge(['photo' => $photoName]); // Mettre à jour la demande avec le nouveau nom d'image
            } else {
                return response()->json(['error' => 'Erreur lors du téléchargement de la photo.'], 400);
            }
        }

        // On met à jour l'utilisateur
        $author->update($request->only(['first_name', 'last_name', 'photo']));

        // On retourne les informations du nouvel utilisateur en format JSON
        return response()->json([
            'status' => 'Mise à jour effectuée avec succès !'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
            // On supprime l'auteur
            $author->delete();

            // On retourne la réponse au format JSON
            return response()->json([
                'status' => 'Suppression effectuée avec succès !'
            ]);
    }
}
