<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NotiUser;

use Validator;

class NotiUserController extends Controller
{
    //

    public function delete($user){

        $notificaciones = NotiUser::where('destino_user',auth()->user()->id)
        ->where('id',$user)
        ->delete();

        
        if($notificaciones){
            return response()->json([
                "code"=>200,
                "response"=>"Notificación eliminada"
            
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "response"=>"Ocurrió un problema la aplicación",
            
            ]);
        }
    }

    public function read($user){

        $notificaciones = NotiUser::where('destino_user',$user)
        ->orderBy('updated_at','desc')
        ->get();

        
        if($notificaciones){
            return response()->json([
                "code"=>200,
                "response"=>"Consultando notificaciones del usuario",
                "notificaciones"=> $notificaciones
            
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "response"=>"Ocurrió un problema la aplicación",
            
            ]);
        }
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'descripcion'=>'required|max:60',
            'origen_user'=>'required',
            'destino_user'=>'required',
           
        ]);

        if($validator->fails()){
            return response()->json([
                "code"=>400,
                "response"=>"Ocurrió un problema la aplicación",
                "errors"=>$validator->messages(),
            
            ]);
        }



        $notificacion = NotiUser::create([

            "descripcion"=>$request->descripcion,
            "tipo"=>"normal",
            "origen_user"=>$request->origen_user,
            "destino_user"=>$request->destino_user,
            
        ]);

        if($notificacion){
            return response()->json([
                "code"=>200,
                "response"=>"Notificación guardada exitosamente",
            
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "response"=>"Ocurrió un problema la aplicación",
            
            ]);
        }

    }
}
