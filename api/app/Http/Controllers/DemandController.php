<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandController extends Controller
{
    public function index(): Collection
    {
        // On veut que les demandes qui sont ouvertes et qui n'ont pas de personne qui va aider la personne dans le besoin
        $demands = Demand::where('state', false)->get();

        return $demands;
    }

    public function store(Request $request): JsonResponse
    {
        if (Auth::user()->volunteer) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à créer une demande.'], 403);
        }

        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $demandData = $request->all();

        $demandData['state'] = false;
        $demandData['created_by'] = Auth::id();

        $address = $this->getAddressFromCoordinates($demandData['latitude'], $demandData['longitude']);
        $demandData['address'] = $address;

        $demand = Demand::create($demandData);

        return response()->json($demand, 201);
    }

    public function show(string $id): JsonResponse
    {
        $demand = Demand::with('potentialVolunteers')->findOrFail($id);

        return response()->json($demand);
    }

    public function update(Request $request, string $id)
    {
        $demand = Demand::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur de la demande
        if ($demand->created_by !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette demande.']);
        }

        $demand->updated_by = Auth::id();
        $demand->update($request->all());

        return $demand;
    }

    public function destroy(string $id): JsonResponse
    {
        $demand = Demand::findOrFail($id);

        // Vérifier si l'utilisateur actuel est le créateur de la demande
        if ($demand->created_by !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer cette demande.']);
        }

        Demand::destroy($id);

        return response()->json(['message' => 'La demande a été supprimé avec succès.']);
    }

    private function getAddressFromCoordinates($latitude, $longitude)
    {
        $client = new Client();
        $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=$latitude&lon=$longitude";

        try {
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            if (isset($data['address'])) {
                return $data['address'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
