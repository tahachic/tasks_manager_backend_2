<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    public function index()
    {
        return response()-> json(MessageResource::collection(Message::with('employee')->get()));
    }

    public function store(Request $request)
    {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'text' => 'required|string',
            'type' => 'required|integer',
            'sender_id' => 'required|exists:employees,id',
            'sender_name' => 'required|string',
            'is_sent' => 'required|boolean',
            'is_seen' => 'required|boolean',
        ]);

        $message = Message::create($request->all());
        return response()->json($message, 201);
    }

    public function show($id)
    {
        return response()->json(new MessageResource(Message::with('employee')->findOrFail($id)) );
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->update($request->all());
        return response()->json($message);
    }

    public function destroy($id)
    {
        Message::destroy($id);
        return response()->json(['message' => 'Message deleted']);
    }

    public function getMessagesByTask($task_id)
{
    // Récupère les messages de la tâche spécifique, triés par date de création
    $messages = Message::where('task_id', $task_id)->with('employee')
                        ->orderBy('created_at', 'asc')
                        ->get();

    // Retourne la liste des messages sous forme de ressource
    //return MessageResource::collection($messages);
    return response()-> json(MessageResource::collection($messages));
}
}
