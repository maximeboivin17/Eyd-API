<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function me(Request $request)
    {
        $user = $request->user();

        //Si l'utilisateur est un volontaire je fournis ses avis
        if ($user){
            $userToReturn = User::with('comments')->find($user);
        } else{
            //Sinon je fournis ses demandes et son/ses handicaps
            $userToReturn = User::with('demands', 'disabilities')->find($user);
        }

        return response()->json($userToReturn);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::all();
        if (count($users) < 1){
            return response(["message" => "Aucune utilisateur enregistré", 200]);
        }
        return response($users, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
