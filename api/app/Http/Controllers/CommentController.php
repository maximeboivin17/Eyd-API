<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(): Collection
    {
        return Comment::all();
    }

    public function store(Request $request)
    {
//        $request->validate([
//            'name' => 'required',
//            'latitude' => 'required',
//            'longitude' => 'required',
//            'state' => 'required',
//            'user_id' => 'required',
//            'event_date' => 'required',
//        ]);

        return Comment::create($request->all());
    }

    public function show(string $id)
    {
        return Comment::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur du commentaire
        if ($comment->disabled_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire.']);
        }

        $comment->update($request->all());

        return $comment;
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur du commentaire
        if ($comment->disabled_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire.']);
        }

        Comment::destroy($id);

        return response()->json(['message' => 'Le commentaire a été supprimé avec succès.']);
    }
}
