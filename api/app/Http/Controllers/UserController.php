<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
        if ($user->volunteer) {
            $userToReturn = User::with('comments')->find($user);
        } else {
            //Sinon je fournis ses demandes et son/ses handicaps
            $userToReturn = User::with('demands', 'disabilities')->find($user);
        }

        return $userToReturn;
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Vérifier si l'utilisateur actuel est l'utilisateur connecté
        if ($user->id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à modifier cette utilisateur.']);
        }

        $user->update($request->all());

        return $user;
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Vérifier si l'utilisateur actuel est l'utilisateur connecté
        if ($user->id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à supprimer cet utilisateur.']);
        }

        User::destroy($id);

        return response()->json(['message' => 'Votre compte a été supprimé avec succès.']);
    }
    


    public function getAllMessages()
    {
        $user = auth()->user();
    
        $conversations = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique(function ($message) use ($user) {
            return $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        });
    
        // Afficher seulement les informations de l'autre utilisateur dans chaque message
        $conversations->map(function ($message) use ($user) {
            if ($message->sender_id === $user->id) {
                $otherUser = User::find($message->receiver_id);
            } else {
                $otherUser = User::find($message->sender_id);
            }
    
            unset($message->sender_id);
            unset($message->receiver_id);
    
            $message->other_user = $otherUser;
    
            return $message;
        });
    
        return response()->json($conversations);
    }

    public function getConversationsWithUser($otherUserId)
    {
        $user = auth()->user();

        $conversations = Message::where(function ($query) use ($user, $otherUserId) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $otherUserId);
        })
        ->orWhere(function ($query) use ($user, $otherUserId) {
            $query->where('sender_id', $otherUserId)
                ->where('receiver_id', $user->id);
        })
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($conversations);
    }
    
    
    
    

}
