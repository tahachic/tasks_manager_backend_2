<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FcmToken;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FirebaseHelper;

class NotificationController extends Controller {
    // Store FCM token
    public function storeToken(Request $request) {
        $request->validate([
            'fcm_token' => 'required|string|unique:fcm_tokens,fcm_token',
        ]);

        $user = Auth::user(); // Assuming authentication is required
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        FcmToken::updateOrCreate(
            ['user_id' => $user->id],
            ['fcm_token' => $request->fcm_token]
        );

        return response()->json(['message' => 'Token stored successfully']);
    }

    // Get all tokens (for testing)
    public function getTokens() {
        return response()->json(FcmToken::all());
    }

    public function sendNotificationToEmployee(Request $request,$id) {

        FirebaseHelper::sendWithCurl('employee_'.$id,$request->title,$request->body);

        
    }
    public function sendNotificationToDepartment(Request $request,$id) {

        FirebaseHelper::sendWithCurl('department_'.$id,$request->title,$request->body);

        
    }
    public function sendNotificationToAll(Request $request) {

        FirebaseHelper::sendWithCurl('all',$request->title,$request->body);

        
    }
    
   
}