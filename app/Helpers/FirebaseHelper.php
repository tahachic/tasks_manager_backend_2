<?php
namespace App\Helpers;

use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use Google\Auth\CredentialsLoader;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Google_Client;
class FirebaseHelper {
    public static function  getAccessToken() {
        $serviceAccountPath = base_path('app/Helpers/tasksmanager-4115f-firebase-adminsdk-fbsvc-bf7324a27d.json');
        $client = new Google_Client();
        $client->setAuthConfig($serviceAccountPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        // Get the access token
        $accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
        return $accessToken;
    }

    public static function sendWithCurl($channel, $titre, $body, $image = null, $screen = null){
        $accessToken = self::getAccessToken();
        $data = [
            "message" => [
                 "topic" => $channel,
                "notification" => [
                    "title" => $titre,
                    "body" => $body,
                    "image" => $image,
                ],
                "android" => [
                    "notification" => [
                        "channel_id" => 'tasks-manager',
                        "notification_priority" => "PRIORITY_HIGH",
                    ],
                ],
                "data" => [
                    "screen" => $screen
                ]
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization: Bearer '.$accessToken,
            'Content-Type: application/json; charset=UTF-8',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/tasksmanager-4115f/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            Log::error('FCM Notification failed: ' . curl_error($ch));
            die('Curl failed: ' . curl_error($ch));
        } 
        // Close connection
        curl_close($ch);
        $response = json_decode($result, true);
        if (isset($response['error'])) {
            Log::error('FCM Error: ' . json_encode($response['error']));
        }
        return $result;
    }


}
