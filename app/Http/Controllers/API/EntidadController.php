<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use DB;
use Storage;
use Auth;
use Google\Cloud\Storage\StorageClient;

use Illuminate\Support\Facades\Cache;

use App\Models\User;

use App\Models\Entidad;
use App\Models\Ubicaciones;
use App\Models\ImagenesEntidad;

use Illuminate\Support\Str;
use App\Http\Controllers\Services\StorageServiceMapper;

class EntidadController extends Controller
{
    //debe haber una forma de validar que el usuario está logeado o que tiene una
    //sesión activa, y después de eso, comprobar que ese usuario sea el mismo del que se 
    //están queriendo consultar la información


    const MIN_DISTANCE = 5000;

    protected $storageMapper;

    public function __construct(StorageServiceMapper $storageMapper)
    {
        $this->storageMapper = $storageMapper;
    }

    public function buscarNombreEntidad(Request $request){
        $textoBusqueda = $request->entidad;
        $entidades = Entidad::where('nombre', 'like', "%$textoBusqueda%")
        ->join('imagenes_entidad','imagenes_entidad.entidad_id','=','entidad.id')
        ->where('imagenes_entidad.type','post')
        ->where('entidad.enabled',1)
        ->where('entidad.status','<>','encontrado')
        ->select('*','entidad.id as entidad_id')
        ->get();

        //dd($entidades);
    
        return view('busqueda_entidad',['entidades'=>$entidades,'searched'=>$textoBusqueda]);
    }

    public function vista_detalles($id){


        $entidad= Entidad::find($id);
        return view('detalles_entidad',["user"=>$entidad->user_id]);
      
       
    }

    public function changeStatusEntidad(Request $request){

        $validator = Validator::make($request->all(), [
            'status'=>'required|in:perdido,sin,encontrado,revision',
          
        ]);

        if($validator->fails()){
            return response()->json([
                "response"=>"Estatus incorrecto",
                "code"=>400,
    
            ]);
        }
        $status = $request->status == "sin" ? "sin definir" : $request->status; 
      

        $entidad = Entidad::where('id',$request->entidad)
        ->where('user_id',auth()->user()->id)
        ->update(['status'=>$status]);
       

        if($entidad){
            return response()->json([
                "response"=>"Estatus cambiado correctamente",
                "code"=>200,

            ]);
        }

    }

    public function detalles_entidad($id){
        

        //revisar si el id del usuario que está mandando coincide con el usuario que estálogeado.

        // puede haber 2 opciones, 1 que el usuario pueda ver sus propios objectos
        //en el menú de inicio o que simplemente no pueda.

    
        $entidad = Entidad::where('id',$id)
        ->where('user_id',auth()->user()->id)
        ->first()
        ;

        // print_r($entidad);
        // die();
       
        if($entidad==null){
            return response()->json([
                "response"=>"La entidad buscada no existe",
                "code"=>500,
    
            ]);
        }else{
            // if(auth()->user()!=null){
            //     //el usuario quiere editar una entidad que no es suya.
            //     if($entidad->user_id != $user){
            //         return response()->json([
            //             "response"=>"La entidad a la que quieres acceder no está disponible",
            //             "code"=>500,
            
            //         ]);
            //     }
            // }else{
            //     //consultando solo datos de la entidad
            // }
            
        }  

        $user = User::find($entidad->user_id);

        //$entidad->imagenes;
        $entidad->ubicaciones;
        return response()->json([
            "entitys"=>$entidad,
            "imgs"=>$entidad->imagenes->where('type','post'),
            'user'=>$user,

        ]);
    }

    public function detalles_entidadNormal($id){
        $entidad = Entidad::where('id',$id)
        //->where('user_id',auth()->user()->id)
        ->first()
        ;
        $user = User::find($entidad->user_id);

        $entidad->ubicaciones;
        return response()->json([
            "entitys"=>$entidad,
            "imgs"=>$entidad->imagenes->where('type','post'),
            'user'=>$user,
        ]);
    }


    public function getEntidadesUser(User $user){
        /*
        if (Auth::guard('sanctum')->check()) {
            // Usuario autenticado

           
          } else {
            
          }
          */
        $requestingUser = auth()->user();

        // echo $user->id;
        // echo $requestingUser->id;
        // die();
        //está autorizado
       if (/*$requestingUser->can('getEntidadesUser', $user)*/ $requestingUser->id == $user->id) {
            $entidades = Entidad::where('user_id',$user->id)->orderBy("entidad.updated_at","desc")
            ->with('locations') 
            ->where('enabled',1)
            ->get();
            $count_contact = 0;
            $img = [];
            $contacts =[];
            $locations = [];
            foreach ($entidades as $key => $entidad) {
              
                $count_contact = sizeof($entidad->contacts);
                $img[]= [
                    $entidad->imagenes
                ];
                $contacts []=[
                   $count_contact
                   
                ];
                $locations[]= [
                    $entidad->locations
                ];
                
            }
            $datos =[
                "response"=>"success",
                "entidades"=>$entidades,
                "imagenes"=>$img,
                "contacts"=>$contacts,
            ];
    
            // dd( $datos);
            return $datos;
        } else {
            //no está autorizado.

            $datos =[
                "response"=>"No está autorizado para revisar esa información",
                "error"=>400,
            ];
    
            // dd( $datos);
            return $datos;
            
        }       
    }

    private function toRandian($dregee){
        $math_pi = 3.141592653589793;
        return $dregee*$math_pi/180;
    }

 
    function distanciaEntrePuntos($deltaLat, $deltaLon,$lat1,$lat2) {
      
        $a = pow(sin($deltaLat/2), 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * pow(sin($deltaLon/2), 2);
        $c = 2 * asin(sqrt($a));
        $EARTH_RADIUS = 6371; // Radio de la tierra en kilómetros
        return $c * $EARTH_RADIUS * 1000; // Distancia en metros
    }

    public function getUbicacionesMovil($lat,$lng){


        $entidades = Entidad::where('enabled', 1)->whereNotIn('status', ['encontrado'])
        ->with([
            'locations' => function ($query) {
               
            },
            'files' => function ($query) {
                $query->where('type', 'post'); 
            }
        ])
        ->get();

        return response()->json($entidades);
         
    }

    public function getUbicaciones($lat,$lng){

        // if (Cache::has('entidades')) {
            
        //     $value = Cache::get('entidades');
        //     return $value;
        // }

        $user = auth()->user();

        if($user==null){
            $f= DB::table('entidad')
            ->select('latitud','longitud','nombre','recompensa','descripcion','entidad_id')
            ->where('ubicaciones.principal',1)
            ->join('ubicaciones','entidad.id','=','ubicaciones.entidad_id')
            ->where('enabled',1)
            ->whereNotIn('status', ['encontrado'])
            ->get();
        }else{
            $f= DB::table('entidad')
            ->select('latitud','longitud','nombre','recompensa','descripcion','entidad_id')
            ->where('ubicaciones.principal',1)
          //  ->whereNot('user_id',$user->id)
            ->join('ubicaciones','entidad.id','=','ubicaciones.entidad_id')
            ->where('enabled',1)
            ->whereNotIn('status', ['encontrado'])
            ->get();
        }

   

        $lng1 = $this->toRandian($lng);
        $lat1= $this->toRandian($lat);


       
         $u=Entidad::get();

       
         $distances = [];
        
         $ubicaciones =[];

        foreach ($f as $key => $point) {
           
            $lat2= $this->toRandian($point->latitud);
            $lng2 = $this->toRandian($point->longitud);

            $deltaLat = $lat2 - $lat1;
            $deltaLon = $lng2 - $lng1;

            $distancia = $this->distanciaEntrePuntos($deltaLat, $deltaLon,$lat1,$lat2);
            $distances=[
                "distancia"=>$distancia,
            ];
            //echo $distancia . "<br>";
            
            // if($distancia < self::MIN_DISTANCE){

                $ubicaciones []=[
                    'latitud' => $point->latitud,
                    'longitud' => $point->longitud,
                    'nombre'=> $point->nombre,
                    'recompensa'=>$point->recompensa,
                    'descripcion'=>$point->descripcion,
                    'entidad_id'=>$point->entidad_id,

                ];

            // }

        
        }

       // return response()->json($distances);

       //Cache::add("entidades", $ubicaciones);

        return $ubicaciones;
         
    }

    //limite de 6 imagenes usuario
    public function registro_img($request,$entidad){
        try{
        if ($request->hasFile('imagenes')) {
         
            foreach ($request->imagenes as $key => $imagen) {
         
                $url=$imagen->getClientOriginalName();
                $url = "entidad".$entidad."_".$url;
                $imagen->storeAs(
                    'public', "entidades\\".$url,"local"
                );               
               $data_id =  ImagenesEntidad::create(["entidad_id"=>$entidad,"archivo"=>$url,"type"=>"post"]);
            
            }
            return response()->json([
                "response"=>$ids,
                "code"=>200,
    
            ]);
        }else{
            return response()->json([
                "response"=>'Es necesario que eliga al menos 1 imagen',
                "code"=>400,
    
            ]);
        }
        
    }catch(\Exception $e){
        return response()->json([
            "response"=>$e->getMessage(),
            "code"=>4042,

        ]);
    }
    }

    public function deleteUpdateImage($entidad){

        $links = ImagenesEntidad::where('entidad_id',$entidad)
          ->where('type','post')
          ->get();

       
        foreach ($links as $key => $link) {
            $file = 'entidades/' . $link->archivo;
                if (Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
        }
          ImagenesEntidad::where('entidad_id',$entidad)
          ->where('type','post')
          ->delete();

    }

    public function editarEntidadMovil(Request $request, $id){
      
        $f = $request->locations;   
        $response = array('response' => '', 'success'=>false);
        $files = $request->file('files');
       

        $validator = Validator::make($request->all(), [
            'nombre'=>'required|max:100',
            'descripcion'=>'required|max:200',
            'fecha_extravio'=>'required',
        ]);
        if($files != null){
            $validator = Validator::make($request->all(), [
                'files.*'=>'required|max:1024|mimes:jpeg,jpg,png,svg',
                'files' => 'required|array',
               
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
            }
        }
    
        DB::beginTransaction();
        try{
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
              } else {
                $id_entidad = "";
                $limit_upimg=false;
                    if($f!=null){

                        $puntos_subir = sizeof($f);

                        $puntos_actuales = Ubicaciones::where('entidad_id',$request->idEnt)
                        ->get();
                        $total = $puntos_subir ;
                        if($total>4){
                            return response()->json([
                                "response"=>'La cantidad de puntos no debe ser mayor a 4 puntos.',
                                "f"=>sizeof($f),
                                "code"=>404,
                            ]
                            );
                        }
                    }

                    $imgs =Entidad::where('user_id',auth()->user()->id)
                    ->where('entidad.id',$id)
                    ->join('imagenes_entidad','imagenes_entidad.entidad_id','=','entidad.id')
                    ->get();

                    if(sizeof($imgs)>4){
                        $limit_upimg=true;
                    }
                    $entidad=Entidad::where('user_id',auth()->user()->id)
                    ->where('id',$id)
                    ->update([
                        'nombre'=>$request->nombre,
                        "descripcion"=>$request->descripcion,
                        "fecha_extravio"=>$request->fecha_extravio,
                        "recompensa"=>$request->recompensa,
                        
                    ]);
                    $id_entidad = $id;
              
                //actualiza la entidad en las imagenes para relacionarlas.

                $imagenes = $request->input('files');
                $nombresArchivos = [];
                if($limit_upimg==false){

                    $this->deleteUpdateImage($id_entidad);
                    foreach ($imagenes as $imagenData) {
                        $imagenBase64 = $imagenData['imagenBase64'];
                        $tipoImagen = $imagenData['type'];
        
                        $imagenDecodificada = base64_decode($imagenBase64);
        
                        if ($imagenDecodificada === false) {
                            return response()->json([
                                'error' => 'Error al decodificar una imagen Base64',
                                'code' => 400,
                            ], 400);
                        }
        
                        $nombreArchivo = Str::random(40) . '.' . explode('/', $tipoImagen)[1];
                        Storage::disk('public')->put('entidades/' . $nombreArchivo, $imagenDecodificada);
                        $nombresArchivos[] = $nombreArchivo;

                        //Save on the bd

                        $data_id =  ImagenesEntidad::create(["entidad_id"=>$id_entidad,"archivo"=>$nombreArchivo,"type"=>"post"]);
                    }
                }
                 
                //check number of points selected.
                if($f!=null){
                    foreach($f as $index=>$ubicacion){
                        $ubicacion = json_decode($ubicacion);
    
                        //Para hacer que el primer punto recibido sea siempre el 'principal'                  
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion->lat,
                                'longitud'=>$ubicacion->lng,
                                'principal'=>0,
                            ]);
                    }
                }
                DB::commit();
                return response()->json([
                    "response"=>"Datos actualizados correctamente",
                    "code"=>200,
                    "entidad_id"=>$id_entidad,
                ]
                );
              }
        }catch(\Exception $e){
        DB::rollback();
        return response()->json([
            "error"=>"Something fails",
            "response"=>$e->getMessage(),
            "line"=>$e->getLine(),
            "code"=>404,
        ]
        );
    }
    }

    public function editarEntidad(Request $request, $id){
      
        $f = $request->locations;   
        $response = array('response' => '', 'success'=>false);
        $files = $request->file('files');
       

        $validator = Validator::make($request->all(), [
            'name'=>'required|max:100',
            'description'=>'required|max:200',
            'date'=>'required',
            //'imagenes'=>'required',
        ]);
        if($files != null){
            $validator = Validator::make($request->all(), [
                'files.*'=>'required|max:1024|mimes:jpeg,jpg,png,svg',
                'files' => 'required|array',
               
            ]);
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
            }
        }
    
        DB::beginTransaction();
        try{
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
              } else {
                $id_entidad = "";
                $limit_upimg=false;
                    if($f!=null){

                        $puntos_subir = sizeof($f);

                        $puntos_actuales = Ubicaciones::where('entidad_id',$request->idEnt)
                        ->get();
                        $total = $puntos_subir + sizeof($puntos_actuales);
                        if($total>4){
                            return response()->json([
                                "response"=>'La cantidad de puntos no debe ser mayor a 4 puntos.',
                                "f"=>sizeof($f),
                                "code"=>404,
                            ]
                            );
                        }
                    }

                    $imgs =Entidad::where('user_id',auth()->user()->id)
                    ->where('entidad.id',$request->idEnt)
                    ->join('imagenes_entidad','imagenes_entidad.entidad_id','=','entidad.id')
                    ->get();

                    if(sizeof($imgs)>4){
                        $limit_upimg=true;
                    }
                    $entidad=Entidad::where('user_id',auth()->user()->id)
                    ->where('id',$request->idEnt)
                    ->update([
                        'nombre'=>$request->name,
                        "descripcion"=>$request->description,
                        "fecha_extravio"=>$request->date,
                        "recompensa"=>$request->reward,
                        
                    ]);
                    $id_entidad = $request->idEnt;
              
                //actualiza la entidad en las imagenes para relacionarlas.

                //$this->registro_img($request,$id_entidad);
                if($limit_upimg==false){
                    $this->ex($files,$id_entidad);
                }
                 
                //check number of points selected.
                if($f!=null){
                    foreach($f as $index=>$ubicacion){
                        $ubicacion = json_decode($ubicacion);
    
                        //Para hacer que el primer punto recibido sea siempre el 'principal'                  
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion->lat,
                                'longitud'=>$ubicacion->lng,
                                'principal'=>0,
                            ]);
                    }
                }
                DB::commit();
                return response()->json([
                    "response"=>$request->isEdit!="1" ? "Datos registrados correctamente"
                    :"Datos actualizados correctamente",
                    "code"=>200,
                    "entidad_id"=>$id_entidad,
                ]
                );
              }
        }catch(\Exception $e){
        DB::rollback();
        return response()->json([
            "error"=>"Something fails",
            "response"=>$e->getMessage(),
            "line"=>$e->getLine(),
            "code"=>404,
        ]
        );
    }




        // if($id!=null){
        //     $entidad = Entidad::find($id);
        //     if($entidad==null){
        //         return redirect()->route('/');
        //     }else{
        //         return view('form_entidad',['id'=>$id]);
        //     }
        // }

        //return view('form_entidad',['id'=>$id]);
       
       
    }
    public function viewEditarEntidad($id=null){

        if($id!=null){
            $entidad = Entidad::find($id);
            if($entidad==null){
                return redirect()->route('/');
            }else{
                return view('editar_entidad',['id'=>$id]);
            }
        }
        return view('editar_entidad',['id'=>$id]);
    
       
       
    }
    public function viewEntidad($id=null){

        if($id!=null){
            $entidad = Entidad::find($id);
            if($entidad==null){
                return redirect()->route('/');
            }else{
                return view('form_entidad',['id'=>$id]);
            }
        }

        return view('form_entidad',['id'=>$id]);
       
       
    }


    public function registro_movil(Request $request){



            $validator = Validator::make($request->all(), [
                'nombre'=>'required|max:100',
                'descripcion'=>'required|max:200',
                'fecha_extravio'=>'required',
                'locations'=>'required', //no lo valida por que no es un 'campo' explícito en el form
                'files' => 'required|array',
                'files.*.imagenBase64' => 'required|string',
                'files.*.type' => 'required|string|in:image/jpeg,image/png,image/svg+xml',
                [
                    'files.required' => "Debe seleccionar al menos 1 punto en el mapa"
                ]

            ]);
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
              }

                     
        $f = $request->locations;   

        $response = array('response' => '', 'success'=>false);
        $imagenes = $request->input('files');
 
         
            if(sizeof($f)>4){
                return response()->json([
                    "response"=>'La cantidad de puntos no debe ser mayor a 4 puntos.',
                    "f"=>sizeof($f),
                    "code"=>404,
                ]
                );
            }
            if(count($imagenes)>4){
                return response()->json([
                    "response"=>'La cantidad de imagenes no debe ser mayor a 4 imagenes.',
                    "f"=>sizeof($f),
                    "code"=>404,
                ]
                );
            }
           
    
        DB::beginTransaction();
        try{
                $id_entidad = "";
                $limit_upimg=false;
                    // if(sizeof($files)>4){
                    //     return response()->json([
                    //         "response"=>'El máximo de imagenes a subir es de 4',
                    //         "code"=>404,
                    //     ]
                    //     );
                    // }

                    $c_e = Entidad::where('user_id',auth()->user()->id)
                    ->where('enabled',1)
                    ->get();
                    if(sizeof($c_e)==5){
                        return response()->json([
                            "response"=>'Ha llegado a su limite de entidades registradas (5)',
                            "code"=>404,
                        ]
                        );
                    }

                    $entidad=Entidad::create([
                        'nombre'=>$request->nombre,
                        "descripcion"=>$request->descripcion,
                        "fecha_extravio"=>$request->fecha_extravio,
                        "recompensa"=>$request->recompensa,
                        "user_id"=>auth()->user()->id, //por defecto
                        'enabled'=>1,
                    ]);
                    $id_entidad = $entidad->id;
                

               

                $nombresArchivos = [];

                if($limit_upimg==false){
                  
                    foreach ($imagenes as $imagenData) {
                        $imagenBase64 = $imagenData['imagenBase64'];
                        $tipoImagen = $imagenData['type'];
        
                        $imagenDecodificada = base64_decode($imagenBase64);
        
                        if ($imagenDecodificada === false) {
                            return response()->json([
                                'error' => 'Error al decodificar una imagen Base64',
                                'code' => 400,
                            ], 400);
                        }
        
                        $nombreArchivo = Str::random(40) . '.' . explode('/', $tipoImagen)[1];
                        Storage::disk('public')->put('entidades/' . $nombreArchivo, $imagenDecodificada);
                        $nombresArchivos[] = $nombreArchivo;

                        //Save on the bd

                        $data_id =  ImagenesEntidad::create(["entidad_id"=>$id_entidad,"archivo"=>$nombreArchivo,"type"=>"post"]);
                    }
                }
                
                //check number of points selected.
                if($f!=null){
                    foreach($f as $index=>$ubicacion){
                       
                        //Para hacer que el primer punto recibido sea siempre el 'principal'
                        if($index == 0  && $request->isEdit !="1"){
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion["latitud"],
                                'longitud'=>$ubicacion["longitud"],
                                'principal'=>1,
                            ]);
                        }else{
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion["latitud"],
                                'longitud'=>$ubicacion["longitud"],
                                'principal'=>0,
                            ]);
                        }
                       
                    }
                }
                DB::commit();
                return response()->json([
                    "response"=>$request->isEdit!="1" ? "Datos registrados correctamente"
                    :"Datos actualizados correctamente",
                    "code"=>200,
                    "entidad_id"=>$id_entidad,
                ]
                );
              
        }catch(\Exception $e){
        DB::rollback();
        return response()->json([
            "error"=>"Something fails",
            "response"=>$e->getMessage(),
            "line"=>$e->getLine(),
            "code"=>404,
        ]
        );

    }
}

    public function registro(Request $request){

        //implementar el contador de puntos en el mapa implica que yo estoy vaciando el array de puntos
        //por lo que el usuario podría salire y estar registrando cada vez más.

        $f = $request->locations;   

        $response = array('response' => '', 'success'=>false);
        $files = $request->file('files');


            $validator = Validator::make($request->all(), [
                'name'=>'required|max:100',
                'description'=>'required|max:200',
                'date'=>'required',
                'locations'=>'required', //no lo valida por que no es un 'campo' explícito en el form
                'files' => 'required|array',
                'files.*'=>'required|max:1024|mimes:jpeg,jpg,png,svg',

                // 'recompensa'=>'required',
            ]);

            // if($f==null){
            //     return response()->json([
            //         "response"=>'Debe seleccionar al menos un punto en el mapa',
            //         "code"=>404,
            //     ]
            //     );
            // }

            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                    "error"=>"validator fails",
                    "code"=>404,
                ]
                );
              }
         
            if(sizeof($f)>4){
                return response()->json([
                    "response"=>'La cantidad de puntos no debe ser mayor a 4 puntos.',
                    "f"=>sizeof($f),
                    "code"=>404,
                ]
                );
            }
           
        

    
        DB::beginTransaction();
        try{
                $id_entidad = "";
                $limit_upimg=false;
                    if(sizeof($files)>4){
                        return response()->json([
                            "response"=>'El máximo de imagenes a subir es de 4',
                            "code"=>404,
                        ]
                        );
                    }

                    $c_e = Entidad::where('user_id',auth()->user()->id)
                    ->where('enabled',1)
                    ->get();
                    if(sizeof($c_e)==5){
                        return response()->json([
                            "response"=>'Ha llegado a su limite de entidades registradas (5)',
                            "code"=>404,
                        ]
                        );
                    }

                    $entidad=Entidad::create([
                        'nombre'=>$request->name,
                        "descripcion"=>$request->description,
                        "fecha_extravio"=>$request->date,
                        "recompensa"=>$request->reward,
                        "user_id"=>auth()->user()->id, //por defecto
                        'enabled'=>1,
                    ]);
                    $id_entidad = $entidad->id;
                

               

                //actualiza la entidad en las imagenes para relacionarlas.

                //$this->registro_img($request,$id_entidad);
                if($limit_upimg==false){
                    $this->ex($files,$id_entidad);
                }
                
                //check number of points selected.
                if($f!=null){
                    foreach($f as $index=>$ubicacion){
                        $ubicacion = json_decode($ubicacion);
    
                        /*
                        return response()->json([
                            "response"=>json_encode($ubicacion)->lat,
                            "error"=>"validator fails",
                            "code"=>404,
                        ]
                        );
                        die();
                        */
                        //Para hacer que el primer punto recibido sea siempre el 'principal'
                        if($index == 0  && $request->isEdit !="1"){
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion->lat,
                                'longitud'=>$ubicacion->lng,
                                'principal'=>1,
                            ]);
                        }else{
                            Ubicaciones::create([
                                'entidad_id'=>$id_entidad,
                                'latitud'=>$ubicacion->lat,
                                'longitud'=>$ubicacion->lng,
                                'principal'=>0,
                            ]);
                        }
                       
                    }
                }
                DB::commit();
                return response()->json([
                    "response"=>$request->isEdit!="1" ? "Datos registrados correctamente"
                    :"Datos actualizados correctamente",
                    "code"=>200,
                    "entidad_id"=>$id_entidad,
                ]
                );
              
        }catch(\Exception $e){
        DB::rollback();
        return response()->json([
            "error"=>"Something fails",
            "response"=>$e->getMessage(),
            "line"=>$e->getLine(),
            "code"=>404,
        ]
        );
    }


      
    }
    public function editar_entidad(Request $request){
        try {
            return response()->json([
                "response"=>'Debe seleccionar al menos un punto en el mapa',
                "code"=>404,
            ]
            );
            $this->registro($request);
        } catch (\Throwable $th) {
            return response()->json([
                "response"=>'Debe seleccionar al menos un punto en el mapa',
                "code"=>404,
            ]
            );
        }
       
    }

    public function DelImgEntidad(Request $request){

        //comprobar si el usuario es el mismo del que quiere eliminar.
        $auth_user = auth()->user();

        if($auth_user != null){           
            if($auth_user->id != $request->userid){
                return response()->json([
                    "response"=>"No disponible",
                    "code"=>400,
                ]);
            }else{
                try{
                    $storageService = $this->storageMapper->getService(env('FILESYSTEM_DRIVER', 'local')); 
                    $storageService->delete_file($request->filename);
                    ImagenesEntidad::where('id',$request->imgid)
                    ->delete();
                    $del="La imagen ha sido eliminada exitosamente";
                    return response()->json([
                        "response"=>"Image delete correctly",
                        "code"=>200,
                        "server" =>$del,
                    ]);
                }catch(Exception $e){
                    $del ="Ocurrió un error al eliminar la imagen";
                    return response()->json([
                        "response"=>"Image delete correctly",
                        "code"=>200,
                        "server" =>$del,
                    ]);
                }
              
            }
        }

        return response()->json([
            "response"=>"Usted no está logeado al parecer",
            "code"=>400,
        ]);

       
    }

    public function ex($files,$entidad)
    {

          // Obtener los archivos enviados en la petición
        //   $files = $request->file('files');
          // Procesar cada archivo
          if($files!=null){
            $storageService = $this->storageMapper->getService(env('FILESYSTEM_DRIVER', 'local')); // Obtener el servicio desde .env
            foreach ($files as $file) {
                // Validar el archivo y moverlo a la carpeta de destino
                $url = $file->getClientOriginalName();
                $url = "entidad".$entidad."_".$url;

               $name = $storageService->store($file, 'entidades', $url);
                
                //local method
              //$file->store('public/entidades');
              //$name = $file->hashName();
                //server method
            //   $object = $bucket->upload(file_get_contents($file), [
            //       'name' => $name
            //   ]);
                     
                
              $data_id =  ImagenesEntidad::create(["entidad_id"=>$entidad,"archivo"=>$name,"type"=>"post"]);
            }
          }
          
      }

      public function deleteEntidad($entidad){

        // $user_id = auth()->user()->id;


            //check user credentails
            $entidad_user = Entidad::find($entidad);

        // if($user_id == $entidad_user->user_id){

            $entidad_user->enabled = 0;

            if($entidad_user->save()){
                return response()->json([
                    'response'=>"Entidad eliminada con éxito",
                    "code"=>200
                ]);
            }

            /*
            $img_entidad = ImagenesEntidad::where('entidad_id',$entidad);
            $images = $img_entidad->get();


            foreach ($images as $key => $img) {
                # code...

                $file = $img->archivo;
                $image_path = public_path().'/storage/entidades/'.$file;
                if (@getimagesize($image_path)) {
                    unlink($image_path);
                }
            }

            $img_entidad->delete();

            $ubicaciones = Ubicaciones::where('entidad_id',$entidad);
            $ubicaciones->delete();

          if($entidad_user->delete()){
            return response()->json([
                'response'=>"Entidad eliminada con éxito",
                "code"=>200
            ]);

           
        }

        */
        // }else{
        //     return response()->json([
        //         'error'=>1,
        //         'response'=>"La Entidad no se ha podido eliminar",
        //         "code"=>400
        //     ]);
        // }

       

      }
       
}

