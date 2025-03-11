<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Message;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Helpers\FirebaseHelper;
use App\Models\Task;
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
        ]);

        $message = Message::create($request->all());
        
        $task = Task::findOrFail($request->task_id);
        $sender = Employee::findOrFail($request->sender_id); // Récupérer l'expéditeur

        // Liste des destinataires
        $recipients = [];
    
        // Ajouter l'employé de la tâche si ce n'est pas l'expéditeur
        if ($task->employee_id != $request->sender_id) {
            $recipients[] = 'employee_' . $task->employee_id;
        }
    
        // Vérifier si supervisors_ids est un tableau ou une chaîne JSON
        $supervisors = is_array($task->supervisors_ids) 
            ? $task->supervisors_ids 
            : json_decode($task->supervisors_ids, true);
    
        // Ajouter les superviseurs en excluant l'expéditeur
        if (is_array($supervisors)) {
            foreach ($supervisors as $supervisor_id) {
                if ($supervisor_id != $request->sender_id) {
                    $recipients[] = 'employee_' . $supervisor_id;
                }
            }
        }
    
        // Envoyer la notification à chaque destinataire
        foreach ($recipients as $recipient) {
            FirebaseHelper::sendWithCurl($recipient, "{$sender->name}", $request->text);
        }
        
        return response()->json(new MessageResource(Message::with('employee')->findOrFail($message->id)), 201);
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
public function markOtherMessagesAsSeen($task_id)
{
    $employee_id = Auth::id(); // Récupérer l'ID de l'utilisateur connecté

    // Mettre à jour uniquement les messages envoyés par une autre personne
    Message::where('task_id', $task_id)
           ->where('sender_id', '!=', $employee_id) // Exclure les messages envoyés par l'utilisateur
           ->update(['is_seen' => true]);

    return response()->json([
        'message' => 'Les messages des autres ont été marqués comme vus.'
    ], 200);
}
}
