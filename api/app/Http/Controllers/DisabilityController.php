<?php

namespace App\Http\Controllers;

use App\Models\Disability;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DisabilityController extends Controller
{
    public function index(): Response
    {
        $demands = Disability::all();
        if (count($demands) < 1){
            return response(["message" => "Aucun handicap"]);
        }
        return response($demands);
    }

    public function show(string $id)
    {
        return Disability::findOrFail($id);
    }
}
