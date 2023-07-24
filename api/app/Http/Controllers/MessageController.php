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


    public function store(Request $request, $receiverId)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return response()->json(['message' => 'Vous devez être connecté pour envoyer un message'], 401);
        }
    
        $user = Auth::user();
    
        $request->validate([
            'content' => 'required',
        ]);
    
        $data = [
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'content' => $request->input('content'),
        ];
    
        $message = Message::create($data);
    
        return response()->json($message, 201);
    }
    
}
