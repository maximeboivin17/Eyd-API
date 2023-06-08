<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class DemandController extends Controller
{
    public function index(): Response
    {
        $demands = Demand::all();
        if (count($demands) < 1){
            return response(["message" => "Aucune demande en attente"]);
        }
        return response($demands);
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

        return Demand::create($request->all());
    }

    public function show(string $id)
    {
        return Demand::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $demand = Demand::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur de la demande
        if ($demand->disabled_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette demande.']);
        }

        $demand->update($request->all());

        return $demand;
    }

    public function destroy(string $id)
    {
        $demand = Demand::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur de la demande
        if ($demand->disabled_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer cette demande.']);
        }

        Demand::destroy($id);

        return response()->json(['message' => 'La demande a été supprimé avec succès.']);
    }
}
