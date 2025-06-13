<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\UserFcmToken;

use App\Models\User;


class NotificacionController extends Controller
{
    //
    public function guardarToken(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        UserFcmToken::updateOrCreate(
            ['user_id' => auth()->id()],
            ['token' => $request->token]
        );

        return response()->json(['message' => 'Token guardado']);
    }





    function enviarNotificacionFCMV1($deviceToken, $titulo, $mensaje, $entity)
    {
        $projectId = config('firebase.project_id');
        $credentialsPath = storage_path('app/firebase/firebase_credentials.json');
        $credentials = json_decode(file_get_contents($credentialsPath), true);
        $now = time();

        $tokenPayload = [
            "iss" => $credentials['client_email'],
            "scope" => "https://www.googleapis.com/auth/firebase.messaging",
            "aud" => $credentials['token_uri'],
            "iat" => $now,
            "exp" => $now + 3600
        ];
    
        $jwt = JWT::encode($tokenPayload, $credentials['private_key'], 'RS256');
    
        // Obtener access token
        $response = Http::asForm()->post($credentials['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);
    
        $accessToken = $response->json()['access_token'];
    
        // Enviar notificación
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
    
        $payload = [
            "message" => [
                "token" => $deviceToken,
                "notification" => [
                    "title" => $titulo,
                    "body" => $mensaje
                ],
               "data" => [
                    "target" => "RequestEvidences",
                    "entity" => "$entity"
                ] 
            ]
        ];
    
        $sendResponse = Http::withToken($accessToken)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($url, $payload);
    
        return $sendResponse->json();
    }

    public function notificar(Request $request)
{

    $token = 'fFnefBUwRTmNR2KtmGhcNR:APA91bFlT8087WCglFSlnYADtSgvhmlim6Gb3KqCWlWLUoqXMyiHIdBQtHBLeGy_HD9aXTAHSAYIkDAlH5dQVvTqinqnK3CbrybGQdAMiN1LkaTB431PBVc';
    return $this->enviarNotificacionFCMV1($token, 'Hola desde Laravel', 'Esto es una notificación con API v1',11);
}

}
