<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        //Si l'utilisateur est un volontaire je fournis ses avis
        if ($user->volunteer){
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
    public function index(): JsonResponse
    {
        $users = User::with(['comments', 'demands', 'disabilities'])->get();

        if ($users->isEmpty()) {
            return response()->json(["message" => "Aucun utilisateur enregistré"], 200);
        }

        $usersToReturn = [];

        foreach ($users as $user) {
            if ($user->volunteer) {
                $userToReturn = User::with('comments')->find($user->id);
            } else {
                $userToReturn = User::with('demands', 'disabilities')->find($user->id);
            }

            $usersToReturn[] = $userToReturn;
        }

        return response()->json($usersToReturn, 200);
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
        $user = User::findOrFail($id);

        //Si l'utilisateur est un volontaire je fournis ses avis
        if ($user->volunteer){
            $userToReturn = User::with('comments')->find($user);
        } else{
            //Sinon je fournis ses demandes et son/ses handicaps
            $userToReturn = User::with('demands', 'disabilities')->find($user);
        }

        return $userToReturn;
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
