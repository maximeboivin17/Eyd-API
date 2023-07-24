<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DemandUserController extends Controller
{
    //TODO: voir l'utilité pcq on peut chopper tous les users dans la demand direct
    public function show(string $demandId)
    {
        $demandUsers = DB::table('demands_users')
            ->where('demand_id', $demandId)
            ->get();

        return response()->json($demandUsers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'demand_id' => 'required|exists:demands,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $demand = Demand::findOrFail($request->input('demand_id'));
        $user = User::findOrFail($request->input('user_id'));

        // TODO: à corriger le retour, ne passe pas dans le catch
        // Empêcher d'avoir 2 lignes identiques
        try {
            $demand->potentialVolunteers()->syncWithoutDetaching([$user->id]);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Vous avez déjà proposé votre aide'], 400);
        }

        return response()->json($request, 201);
    }

    public function update(Request $request, string $id)
    {
        // Remplace le findOrFail vu qu'on a pas de model
        $demandUser = DB::table('demands_users')
            ->where('id', $id)
            ->first();

        if (!$demandUser) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $demand = Demand::find($demandUser->demand_id);

        if (Auth::id() !== $demand->created_by) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cela.']);
        }

        DB::table('demands_users')
            ->where('id', $id)
            ->update(['accepted' => $request->input('accepted')]);

        // Supprimer les autres lignes liées à la demande
        DB::table('demands_users')
            ->where('demand_id', $demand->id)
            ->where('id', '!=', $id)
            ->delete();

        $updatedDemandUser = DB::table('demands_users')
            ->where('id', $id)
            ->first();

        // Création du message d'initialisation de la conversation
        $message = new Message();
        $message->sender_id = auth()->id();
        $message->receiver_id = $updatedDemandUser->user_id;
        $message->content = "Conversation initialisée.";
        $message->save();

        return response()->json($updatedDemandUser);
    }
}
