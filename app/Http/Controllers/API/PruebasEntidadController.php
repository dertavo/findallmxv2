<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PruebasEntidad;
use App\Models\ContactUser;
use App\Models\Entidad;
use App\Models\User;
use DB;
use App\Models\ImagenesEntidad;
use App\Models\NotiUser;
use Validator;
use Illuminate\Support\Str;

use Google\Cloud\Storage\StorageClient;

use App\Http\Controllers\Services\StorageServiceMapper;


use App\Http\Controllers\NotificacionController;


class PruebasEntidadController extends Controller
{

    const NUM_EVIDENCE = 2;

    public function __construct(StorageServiceMapper $storageMapper)
    {
        $this->storageMapper = $storageMapper;
    }

    public function handShake($id){
       
        PruebasEntidad::where('id',$id)
        ->update([
            "handshake"=>1
        ]);
        
        $pe= PruebasEntidad::where('id',$id)
        ->first();

        Entidad::where('id',$pe->entidad_id)
        ->update([
            'status'=>'encontrado'
        ]);

        return redirect()->back()->with("success","Trato finalizado correctamente");
    }

    public function handShakeMovil($id){
       
        PruebasEntidad::where('id',$id)
        ->update([
            "handshake"=>1
        ]);
        
        $pe= PruebasEntidad::where('id',$id)
        ->first();

        Entidad::where('id',$pe->entidad_id)
        ->update([
            'status'=>'encontrado'
        ]);

        return response()->json([
            "response" =>"Trato finalizado correctamente",
            "code"=>200,

        ]);
        
    }

    public function acceptedContactMovil($id){
        //para aceptar un contacto primero se debe ver que realmente existe el 'contacto'
        //si existe el contacto entonces se acepta y cambia de estatus

        //el id es mientras no surga errores.
    
        PruebasEntidad::where('id',$id)
        ->update([
            "status"=>"aceptado"
        ]);

        return response()->json([
            "response" =>"Evidencia aceptada correctamente",
            "code"=>200,

        ]);

    }

    
    //posibilidad de que estás 2 funciones se hagan solo 1.
    public function acceptedContact($id){
        //para aceptar un contacto primero se debe ver que realmente existe el 'contacto'
        //si existe el contacto entonces se acepta y cambia de estatus

        //el id es mientras no surga errores.
    
        PruebasEntidad::where('id',$id)
        ->update([
            "status"=>"aceptado"
        ]);

        return redirect()->back();

    }

    public function declinedContactMovil($id){
        //para aceptar un contacto primero se debe ver que realmente existe el 'contacto'
        //si existe el contacto entonces se acepta y cambia de estatus

        //el id es mientras no surga errores.
    
        PruebasEntidad::where('id',$id)
        ->update([
            "status"=>"rechazada"
        ]);

        return response()->json([
            "response" =>"Evidencia rechazada con éxito",
            "code"=>200,

        ]);
        


    }

    public function declinedContact($id){
        //para aceptar un contacto primero se debe ver que realmente existe el 'contacto'
        //si existe el contacto entonces se acepta y cambia de estatus

        //el id es mientras no surga errores.
    
        PruebasEntidad::where('id',$id)
        ->update([
            "status"=>"rechazada"
        ]);
        return redirect()->back();

    }

    public function delEvidence($evidence){

        $ev = PruebasEntidad::where('id',$evidence)
        ->where('contact_user',auth()->user()->id)
        ->first();

        if($ev->status =="rechazada" || $ev->status == "revision"){
            $evidencia = PruebasEntidad::where('id',$evidence)
            ->where('contact_user',auth()->user()->id)
            ->delete();
    
            $contact = ContactUser::where('evidence_id',$evidence)
            ->delete();
    
            $imagenes = ImagenesEntidad::where('type','evidence')
            ->where('evidence_id',$evidence)
            ->get();
            foreach ($imagenes as $key => $imagen) {

                ImagenesEntidad::where('id',$imagen->id)
                ->delete();
           
          
            // try{
            //       //eliminar imagen del bucket.
            //     $storage = new StorageClient();
            //     $bucket = $storage->bucket('findall_bucket');
               
            //     $object = $bucket->object($imagen->archivo);
            //     $object->delete();
            //     $del="La imagen ha sido eliminada exitosamente";
            // }catch(Exception $e){
            //     $del ="Ocurrió un error al eliminar la imagen";
            //     // return response()->json([
            //     //     "response"=>"Image delete correctly",
            //     //     "code"=>200,
            //     //     "server" =>$del,
            //     // ]);
            // }
    
            }
            $del="La imagen ha sido eliminada exitosamente";
            if($evidencia){
                return response()->json([
                    "response" =>"Evidencia eliminada con éxtio",
                    "code"=>200,
                    "details" => $del
        
                ]);
            }
        }else{
            return response()->json([
                "response" =>"Sólo se pueden eliminar evidencias rechazadas",
                "code"=>400,
    
            ]);
        }


        
    }

    public function findedAPI($user){

        $username = DB::table('users')->where('id',$user)->select('username')->first();
        $username = $username->username;
       
        $prueba = PruebasEntidad::with('imagenesEntidad', 'entidad','destinoUser','contactUser') 
        ->where('contact_user',$user)
        ->get();
        $prueba->makeHidden([
            'contact_user', 'destino_user'
        ]);


        // ->select('pruebas_entidad.entidad_id as entidad_id',
        // 'pruebas_entidad.handshake','entidad.nombre','pruebas_entidad.status as entity_status',"users.username as username",
        // "pruebas_entidad.id as prueba_id",
        // "pruebas_entidad.descripcion as mides",
        // "pruebas_entidad.destino_user",
        // "imagenes_entidad.archivo",        
        // )
        // ->where('contact_user',$user)
        // ->get();
        

        return response()->json([
            "response"=>"success",
            "code" => 200, 
            "message"=>"ok",
            "origin_username" =>$username,
            "entitys" => $prueba
        ]);

    

        try {

         

            //code...
            $entitys = DB::table('pruebas_entidad')
            ->select('entidad.id as entidad_id',
            'pruebas_entidad.handshake','entidad.nombre','pruebas_entidad.status as entity_status',"users.username as username",
            "pruebas_entidad.id as prueba_id",
            "pruebas_entidad.descripcion as mides",
            "pruebas_entidad.destino_user",
            "imagenes_entidad.archivo",
            
            )
            ->join('users','users.id','=','pruebas_entidad.destino_user')
            ->join('entidad','entidad.id','=','pruebas_entidad.entidad_id')
            ->join('imagenes_entidad','imagenes_entidad.evidence_id','=','pruebas_entidad.id')
            ->where('contact_user',$user)
            ->orderBy('pruebas_entidad.updated_at','desc')
            // ->where('enabled',1)
            
            ->get();

            //dd($entitys);
            $username = DB::table('users')->where('id',$user)->select('username')->first();
            $username = $username->username;
       
                    
            // $final_user = DB::table('contacto_user')
            // ->join('users','users.id','=','contacto_user.usuario_final')
            // ->where('usuario_contacto',$user)
            // ->get();
           
            //$user = User::find($final_user)->first();

            return response()->json([
                "response"=>"success",
                "code" => 200, 
                "message"=>"ok",
                "origin_username" =>$username,
                "entitys" => $entitys
            ]);

  
        } catch (\Throwable $th) {
            
            return response()->json([
                "response"=>"error",
                "code"=>400,
                "message"=> "No data founded"
                
            ]);
        }
      
    }

    public function finded($user){
        try {
            //code...
            $entitys = DB::table('pruebas_entidad')
            ->select('entidad.id',
            'pruebas_entidad.handshake','entidad.nombre','pruebas_entidad.status as entity_status',"users.username as username",
            "pruebas_entidad.id as prueba_id",
            "pruebas_entidad.descripcion as mides",
            "pruebas_entidad.destino_user",
            "imagenes_entidad.archivo",
            )
            ->join('users','users.id','=','pruebas_entidad.destino_user')
            ->join('entidad','entidad.id','=','pruebas_entidad.entidad_id')
            ->join('imagenes_entidad','imagenes_entidad.evidence_id','=','pruebas_entidad.id')
            ->where('contact_user',$user)
            ->orderBy('pruebas_entidad.updated_at','desc')
            // ->where('enabled',1)
            ->get();

            // dd($entitys);


                    
            // $final_user = DB::table('contacto_user')
            // ->join('users','users.id','=','contacto_user.usuario_final')
            // ->where('usuario_contacto',$user)
            // ->get();
           
            //$user = User::find($final_user)->first();

            return view("user/finded_entitys",["entitys"=>$entitys]);
        } catch (\Throwable $th) {
            //throw $th;
            //pure laravel

            return redirect()->back()->with("error","No data founded");
        }
      
    }


    public function contacts($entidad){
        $contacts = PruebasEntidad::where('entidad_id',$entidad)->orderBy("updated_at","desc")
        ->get();
     

        //cambiar a eloquent luego
        $contacts = DB::table('pruebas_entidad')
        ->select('*','pruebas_entidad.id','pruebas_entidad.created_at as prueba_fecha')
        ->join('users','users.id','=','pruebas_entidad.contact_user')
        ->join('imagenes_entidad','imagenes_entidad.evidence_id','=','pruebas_entidad.id')
        ->where('pruebas_entidad.entidad_id',$entidad)
        ->orderBy('pruebas_entidad.updated_at','desc')
        ->get();

        //  dd($contacts);
    
      
       $entidad = Entidad::where("id",$entidad)
       ->select('id','nombre')
       ->first();

        //get info of user
        
        //laravel mode



        return view("user/contacts",["contacts"=>$contacts,"entidad"=>$entidad,"countEvidence"=>count($contacts)]);

        //api mode 
        //return $datos;
    }

    public function contactsMovil($entidad){
      
        //cambiar a eloquent luego
        $contacts = DB::table('pruebas_entidad')
        ->select('*','pruebas_entidad.id','pruebas_entidad.created_at as prueba_fecha')
        ->join('users','users.id','=','pruebas_entidad.contact_user')
        ->join('imagenes_entidad','imagenes_entidad.evidence_id','=','pruebas_entidad.id')
        ->where('pruebas_entidad.entidad_id',$entidad)
        ->orderBy('pruebas_entidad.updated_at','desc')
        ->get();

        if($contacts->isEmpty()){
            return response()->json([
                "code"=>201,
                "error_message" => "No evidence"
                ]);
        }

        $origin_username = $contacts[0]->destino_user; 

        $origin_username = User::where("id",$origin_username)
        ->select("username")
        ->first()->username;
        
      
       $entidad = Entidad::where("id",$entidad)
       ->select('id','nombre')
       ->first();

       return response()->json([
        "code"=>200,
        "contacts"=>$contacts,
        "entidad"=>$entidad,
        "origin_username" =>$origin_username,
        "countEvidence" => count($contacts)
        ]);

    }

    public function view($entidad){

       

        $contacto_entidad = Entidad::where('id',$entidad)->select('user_id','status')->first();
        $user_d = User::where('id',$contacto_entidad->user_id)->select('id')->first();
       
        if($contacto_entidad->status=="encontrado"){
           
            return redirect()->back()->with('founded', "Gracias por tu interés, ¡el objecto ya ha sido encontrado!");
        }else{
            return view('contacto_entidad',["entidad"=>$entidad,"usuario_destino"=>$user_d]);
        }

       
        
      
    }

    public function contactUserMovil(Request $request){
        //debe mandar la información de contacto del usuario,
        //así como "evidencia" que sostenga que realmente se ha encontrado el objecto extravíado.

        //es de IMPORTANCIA que todos los usuarios debén de tener toda su información completa.
        //de lo contrario no podrá acceder a esta "funcionalidad"
      
        $validator = Validator::make($request->all(), [
            'description'=>'required|max:255',
            'contact_user'=>'required',
            'destination_user'=>'required',
            'contact_entity'=>'required',
            'files' => 'required|array',
            //'files.*.imagenBase64' => 'required|string',
            //'files.*.tipo' => 'required|string|in:image/jpeg,image/png,image/svg+xml',
           
        ]);


        //no te puedes poner en contacto con tu propia entidad
        //no te puedes poner en contacto con una entidad que ya tiene status "encontrado" 
        //tu usuario contacto no puede ser diferente del que estás logeado.
        $id_user = auth()->user()->id;
        $val=[];
        if($id_user == $request->destination_user){
            $val[]=[
                "El usuario actual no puede ser el destino de la evidencia"
            ];
        }

        if($id_user != $request->contact_user){
            $val[]=[
                "Usuario no válido para la operación actual"
            ];
        }
        $entidad = Entidad::where('id',$request->contact_entity)
        ->first();

        if($entidad==null){
            $val[]=[
                "La entidad proporcionada  no existe"
            ];
        }else if ($entidad->status =="encontrado"){
            $val[]=[
                "La entidad actualmente no recibe evidencias"
            ];
        }

        $destino = User::find($request->destination_user);
        if(empty($destino)){
            $val[]=[
                "El usuario destino no existe"
            ];
        }
        //comprobar que el usuario destino si le pertenezca la entidad

        $comp = Entidad::where('id',$request->contact_entity)
        ->where('user_id',$request->destination_user)->first();
        if(empty($comp)){
            $val[]=[
                "La entidad proporcionada no le corresponde a el usuario dado"
            ];
        }
        if(sizeof($val)!=0){
            return response()->json([
                "error_code"=>400,
                "response"=>$val,
            ]);

        }

        $usuario_final = $entidad->user_id;
        $user = User::find($usuario_final);

       
        // print_r($val);
        // die();


        //verificar cuantas evidencias he mandado sobre una entidad

        $numEvidence = PruebasEntidad::where('contact_user',$request->contact_user)
        ->where('entidad_id',$request->contact_entity)
        ->get();

        if(sizeof($numEvidence)>=self::NUM_EVIDENCE){
            return response()->json([
                "response"=> "Ha superado la cantidad de evidencias permitidas",
                "error"=>"validator fails",
                "error_code"=>404,
            ]
            );
          }  
        

        if ($validator->fails()) {
            return response()->json([
                "response"=>$validator->messages(),
                "error"=>"validator fails",
                "error_code"=>404,
            ]
            );
          } 
        DB::beginTransaction();
        try {
            $pruebas_entidad = PruebasEntidad::create([
                "descripcion"=>$request->description,
                "status" =>"revision",
                "handshake"=>0,
                // "archivo"=>$name,
                "contact_user"=>$request->contact_user,
                "destino_user"=>$request->destination_user,
                "entidad_id"=>$request->contact_entity,
            ]);
    
            //se notifica
            $notificacion = NotiUser::create([
                "descripcion"=>"He encontrado tu objecto perdido",
                "tipo"=>"normal",
                "origen_user"=>$request->contact_user,
                "destino_user"=>$request->destination_user,
                "entidad"=>$request->contact_entity,
                
            ]);
          
            //
            $imagenes = $request->input('files');
            $storageService = $this->storageMapper->getService(env('FILESYSTEM_DRIVER', 'local')); 
            $nombresArchivos = [];
            if ($imagenes) {

                if(sizeof($imagenes)>2){
                    DB::rollback();
                    return response()->json([
                        "response"=>"La cantidad máxima de imagenes es 2",
                        "error"=>"validator fails",
                        "error_code"=>404,
                    ]
                    );
                }

                foreach ($imagenes as $imagenData) {
                    $imagenBase64 = $imagenData['imagenBase64'];
                    $tipoImagen = $imagenData['type'];
    
                    $imagenDecodificada = base64_decode($imagenBase64);
    
                    if ($imagenDecodificada === false) {
                        return response()->json([
                            'error' => 'Error al decodificar una imagen Base64',
                            'error_code' => 400,
                        ], 400);
                    }
    
                    $nombreArchivo = Str::random(40) . '.' . explode('/', $tipoImagen)[1];
                    //Storage::disk('public')->put('entidades/' . $nombreArchivo, $imagenDecodificada);
                    $nombresArchivos[] = $nombreArchivo;
                    $storageService->storeDisk($nombreArchivo,$imagenDecodificada);
                         
                  $data_id =  ImagenesEntidad::create(
                    ["entidad_id"=>$request->contact_entity,
                    "archivo"=>$nombreArchivo,
                    "type"=>"evidence",
                    "evidence_id"=>$pruebas_entidad->id,
                  ]);
                }

           
            
               
            }else{
                DB::rollback();
                return response()->json([
                    "response"=>'Es necesario que eliga al menos 1 imagen',
                    "error_code"=>400,
        
                ]);
            }

            $contact_user= ContactUser::create([
                "usuario_contacto"=> $request->contact_user ,
                'evidence_id' =>$pruebas_entidad->id,
                "usuario_final"=> $usuario_final,
            ]);

            DB::commit();
           } catch (\Throwable $th) {
            DB::rollback();
            $msj_error[]=[
                "msj" => $th->getMessage(),
                "line" => $th->getLine(),
                "error_code"=>300,
            ];
           }
      
       if(!empty($msj_error)){
        DB::rollback();
        return response()->json([
            'response'=>"Ocurrió un error en su aplicación",
            'details'=>$msj_error,
            'error_code'=>400,
            
        ]);
       }else{

        $user = User::find($request->destination_user); // Suponiendo que quieres el usuario con ID 1

        if ($user && $user->fcmToken) {
            $token = $user->fcmToken->token; // Suponiendo que tu modelo UserFcmToken tiene una columna 'token'
            
            $noty = new NotificacionController();
            $noty->enviarNotificacionFCMV1($token, "Entidad encontrada","He encontrado tu entidad perdida",$request->contact_entity);

        } else {
           // die('Este usuario no tiene un token FCM asociado.');
        }
            
        return response()->json([
            'response'=>"Se ha puesto en contacto con el usuario correctamente",
            'usuario'=>$usuario_final,
            'code'=>200,
            
        ]);
       }
    }

    public function contactUser(Request $request){
        //debe mandar la información de contacto del usuario,
        //así como "evidencia" que sostenga que realmente se ha encontrado el objecto extravíado.

        //es de IMPORTANCIA que todos los usuarios debén de tener toda su información completa.
        //de lo contrario no podrá acceder a esta "funcionalidad"
      
        $validator = Validator::make($request->all(), [
            'descripcion'=>'required|max:255',
            'usuario_contacto'=>'required',
            'usuario_destino'=>'required',
            'entidad_contacto'=>'required',
            'imagenes.*'=>'required|max:1024|mimes:jpeg,jpg,png,svg',
           
        ]);


        //no te puedes poner en contacto con tu propia entidad
        //no te puedes poner en contacto con una entidad que ya tiene status "encontrado" 
        //tu usuario contacto no puede ser diferente del que estás logeado.
        $id_user = auth()->user()->id;
        $val=[];
        if($id_user == $request->usuario_destino){
            $val[]=[
                "El usuario actual no puede ser el destino de la evidencia"
            ];
        }

        if($id_user != $request->usuario_contacto){
            $val[]=[
                "Usuario no válido para la operación actual"
            ];
        }
        $entidad = Entidad::where('id',$request->entidad_contacto)
        ->first();

        if($entidad==null){
            $val[]=[
                "La entidad proporcionada  no existe"
            ];
        }else if ($entidad->status =="encontrado"){
            $val[]=[
                "La entidad actualmente no recibe evidencias"
            ];
        }

        $destino = User::find($request->usuario_destino);
        if(empty($destino)){
            $val[]=[
                "El usuario destino no existe"
            ];
        }
        //comprobar que el usuario destino si le pertenezca la entidad

        $comp = Entidad::where('id',$request->entidad_contacto)
        ->where('user_id',$request->usuario_destino)->first();
        if(empty($comp)){
            $val[]=[
                "La entidad proporcionada no le corresponde a el usuario dado"
            ];
        }
        if(sizeof($val)!=0){
            return response()->json([
                "code"=>400,
                "response"=>$val,
            ]);

        }

        $usuario_final = $entidad->user_id;
        $user = User::find($usuario_final);

       
        // print_r($val);
        // die();


        //verificar cuantas evidencias he mandado sobre una entidad

        $numEvidence = PruebasEntidad::where('contact_user',$request->usuario_contacto)
        ->where('entidad_id',$request->entidad_contacto)
        ->get();

        if(sizeof($numEvidence)>=self::NUM_EVIDENCE){
            return response()->json([
                "response"=> "Ha superado la cantidad de evidencias permitidas",
                "error"=>"validator fails",
                "code"=>404,
            ]
            );
          }  
        

        if ($validator->fails()) {
            return response()->json([
                "response"=>$validator->messages(),
                "error"=>"validator fails",
                "code"=>404,
            ]
            );
          } 
        DB::beginTransaction();
        try {
            $pruebas_entidad = PruebasEntidad::create([
                "descripcion"=>$request->descripcion,
                "status" =>"revision",
                "handshake"=>0,
                // "archivo"=>$name,
                "contact_user"=>$request->usuario_contacto,
                "destino_user"=>$request->usuario_destino,
                "entidad_id"=>$request->entidad_contacto,
            ]);
    
            //se notifica
            $notificacion = NotiUser::create([
                "descripcion"=>"He encontrado tu objecto perdido",
                "tipo"=>"normal",
                "origen_user"=>$request->usuario_contacto,
                "destino_user"=>$request->usuario_destino,
                "entidad"=>$request->entidad_contacto,
                
            ]);
          
            //
            if ($request->hasFile('imagenes')) {

                if(sizeof($request->imagenes)>2){
                    DB::rollback();
                    return response()->json([
                        "response"=>"La cantidad máxima de imagenes es 2",
                        "error"=>"validator fails",
                        "code"=>404,
                    ]
                    );
                }

                // $storage = new StorageClient();
                // $bucket = $storage->bucket("findall_bucket");
             

                $storageService = $this->storageMapper->getService(env('FILESYSTEM_DRIVER', 'local')); // Obtener el servicio desde .env
                foreach ($request->imagenes as $key => $imagen) {
                    $url = uniqid() . '.' . $imagen->getClientOriginalExtension();
                   $url = "entidad_evidence".$request->entidad_contacto."_".$url;
                   
                //    $imagen->storeAs(
                //         'public', "pruebasContacto\\".$url,"local"
                //     );   
                    

                    $name = $storageService->storeAs($imagen, 'entidades', $url);
                    
                    //$name = $imagen->hashName();
                    //server method
                //   $object = $bucket->upload(file_get_contents($imagen), [
                //       'name' => $name
                //   ]);
                         
                  $data_id =  ImagenesEntidad::create(
                    ["entidad_id"=>$request->entidad_contacto,
                    "archivo"=>$name,
                    "type"=>"evidence",
                    "evidence_id"=>$pruebas_entidad->id,
                  ]);
                }
               
            }else{
                DB::rollback();
                return response()->json([
                    "response"=>'Es necesario que eliga al menos 1 imagen',
                    "code"=>400,
        
                ]);
            }

            $contact_user= ContactUser::create([
                "usuario_contacto"=> $request->usuario_contacto ,
                'evidence_id' =>$pruebas_entidad->id,
                "usuario_final"=> $usuario_final,
            ]);

            DB::commit();
           } catch (\Throwable $th) {
            DB::rollback();
            $msj_error[]=[
                "msj" => $th->getMessage(),
                "line" => $th->getLine(),
                "error"=>300,
            ];
           }
      
       if(!empty($msj_error)){
        DB::rollback();
        return response()->json([
            'response'=>"Ocurrió un error en su aplicación",
            'details'=>$msj_error,
            'code'=>400,
            
        ]);
       }else{
        return response()->json([
            'response'=>"Se ha puesto en contacto con el usuario correctamente",
            'usuario'=>$usuario_final,
            'code'=>200,
            
        ]);
       }
    }
}
