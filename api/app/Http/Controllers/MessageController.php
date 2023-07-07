<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::all();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required',
        ]);

        // Vérifier si l'utilisateur qui veut envoyer un message est l'utilisateur connecté
        if ($request->sender_id !== Auth::id()) {
            return response()->json(['message' => 'Vous n\'êtes pas autorisé à créer un message pour un autre utilisateur']);
        }

        $message = Message::create($request->all());

        return response()->json($message, 201);
    }
}
