<?php

namespace App\Http\Controllers;

use App\Models\Disability;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DisabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $demands = Disability::all();
        if (count($demands) < 1){
            return response(["message" => "Aucun handicap", 200]);
        }
        return response($demands, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Disability::findOrFail($id);
    }
}
