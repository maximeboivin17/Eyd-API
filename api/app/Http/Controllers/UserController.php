<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me()
    {
        $userId = Auth::id();

        if (Auth::user()->volunteer) {
            // Si l'utilisateur est un volontaire, fournir ses avis
            $userToReturn = User::with('comments')->where('id', $userId)->first();
        } else {
            // Sinon, fournir ses demandes et son/ses handicaps
            $userToReturn = User::with('demands', 'disabilities')->where('id', $userId)->first();
        }

        return $userToReturn;
    }


    public function index(): JsonResponse
    {
        $users = User::all();

        $usersToReturn = [];

        foreach ($users as $user) {
            if ($user->volunteer) {
                $userToReturn = User::with('comments')->find($user->id);
            } else {
                $userToReturn = User::with('demands', 'disabilities')->find($user->id);
            }

            $usersToReturn[] = $userToReturn;
        }

        return response()->json($usersToReturn);
    }

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

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Vérifier si l'utilisateur actuel est l'utilisateur connecté
        if ($user->id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette utilisateur.']);
        }

        $user->update($request->all());

        return $user;
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Vérifier si l'utilisateur actuel est l'utilisateur connecté
        if ($user->id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer cet utilisateur.']);
        }

        User::destroy($id);

        return response()->json(['message' => 'Votre compte a été supprimé avec succès.']);
    }
}
