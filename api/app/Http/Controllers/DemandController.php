<?php

namespace App\Http\Controllers;

use App\Models\Demand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $demands = Demand::all();
        if (count($demands) < 1){
            return response(["message" => "Aucune demande en attente", 200]);
        }
        return response($demands, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Demand::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $demand = Demand::findOrFail($id);
        $demand->update($request->all());

        return $demand;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Demand::destroy($id);
    }
}
