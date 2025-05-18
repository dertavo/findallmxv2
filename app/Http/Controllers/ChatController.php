<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ChatController extends Controller
{

    const LIMIT_MSSG=5;

    private function numMessage($origen,$destino,$entidad){
    
        $messages = Message::where('origen_user',$origen)
        ->where('destino_user',$destino)
        ->where('entidad',$entidad)
        ->get();

        return $messages->count();
    }

    public function read($origen,$destino,$entidad){

     
        $messages = Message::where('origen_user',$origen)
        ->where('destino_user',$destino)
        ->where('entidad',$entidad)
        ->orWhere(function($query) use ($origen, $destino,$entidad) {
            $query->where('origen_user', $destino)
                  ->where('destino_user', $origen)
                  ->where('entidad',$entidad)
                  ;
        })
        ->get();

       
        if($this->numMessage($origen,$destino,$entidad) >= self::LIMIT_MSSG){
            return response()->json([
                "end" =>true,
                "response"=>"Ha llegado al limite de mensajes disponibles",
                "messages"=> $messages,
                "code"=>200,
            ]);
        }else{
           
            return response()->json([
                "end"=>false,
                "response"=>"Mensajes obtenidos",
                "messages"=> $messages,
                "code"=>200,
            ]);
        }
    }

    public function index()
    {
        $messages = Message::all();
        return view('chat', compact('messages'));
    }

    public function store(Request $request)
    {
        if($this->numMessage($request->origen_user,$request->destino_user,$request->entidad_id) >= self::LIMIT_MSSG){
            return response()->json([
                "response"=>"Ha llegado al limite de mensajes disponibles",
               // "messages"=> $messages,
                "code"=>400,
            ]);
        }else{

        $message = new Message();
        $message->entidad = $request->input('entidad_id');
        $message->origen_name = $request->input('origen_name');
        $message->destino_name = $request->input('destino_name');
        $message->origen_user = $request->input('origen_user');
        $message->destino_user = $request->input('destino_user');
        $message->content = $request->input('content');
        $message->save();
        
        return response()->json([
            "response"=>"Mensaje guardado con exito",
            "message"=> $message,
            "code"=>200,
        ]);
        }
    }
}
