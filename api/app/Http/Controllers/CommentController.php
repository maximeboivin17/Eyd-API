<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(): Collection
    {
        return Comment::all();
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'note' => 'required',
            'volunteer_id' => 'required',
        ]);

        $commentData = $request->all();

        //TODO: Changer ça pour mettre l'utilisateur mettant l'avis avec Auth::id() et automatiquement le volunteer_id de la demand ici
        $commentData['created_by'] = Auth::id();

        $comment = Comment::create($commentData);

        return response()->json($comment, 201);
    }

    public function show(string $id)
    {
        return Comment::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $comment = Comment::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur du commentaire
        if ($comment->created_by !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier ce commentaire.']);
        }

        $comment->updated_by = Auth::id();
        $comment->update($request->all());

        return $comment;
    }

    public function destroy(string $id): JsonResponse
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
