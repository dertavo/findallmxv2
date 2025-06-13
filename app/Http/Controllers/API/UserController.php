<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Controllers\NotificacionController;


use Illuminate\Http\Request;
use Validator;
use Hash;  
use Auth;
use DB;
use Google\Cloud\Storage\StorageClient;

use Dompdf\Dompdf;

use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\NotiUser;
use App\Models\UserInfo;

use App\Http\Requests\UserInfoRequest; 



use Laravel\Sanctum\PersonalAccessToken;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmEmail;
use App\Mail\RecoveryPass;
use Illuminate\Support\Str;




class UserController extends Controller
{
    //


    public function changePass(Request $request){

      
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'old_pass' => 'required',
            'new_pass' => 'required|min:8|different:old_pass',
        ]);

        if($validator->fails()){
            return response()->json([
                "response"=>"Verifique los datos proporcionados",
                "code"=>400,
                "errors"=>$validator->messages(),
            ]);
        }
        $credentials = [
            "email"=>$request->email,
            "password"=>$request->old_pass,
        ];
       


        if (Auth::guard('web')->attempt($credentials)) {
            // La autenticación fue exitosa

            $up = User::where('email',$request->email)
            ->update([
                "password"=>bcrypt($request->new_pass),
            ]);

            return response()->json([
                "response"=>"Contraseña actualizada con éxito",
                "code"=>200,
            ]);
           
        } else {
            return response()->json([
                "response"=>"Verifique los datos proporcionados",
                "code"=>400,
                "errors"=>"Su antigüa contraseña no coincide"
            ]);
        }


    }

    public function contactProfile(User $user){
      
        return view("user.contact-profile",["user"=>$user]);
    }

    public function myProfile(Request $request){

     
        $requestingUser = auth()->user();
      
        if($requestingUser->id == $request->user){
            return response()->json([
                "response" => "Success",
                "user"=>$requestingUser,
                "status"=>200,
            ]);
           
        }else{
            return response()->json([
                "status"=>404,
                "response" => "Inaccesible",
                "error"=>404,
                "ruta"=>"my profile",
                    // "r"=>$request->user,
                    // "r2"=>$requestingUser,
            ]);
        }
    }
   

    public function hola(){

        $tokenId=14;
        $token = PersonalAccessToken::find($tokenId);

        print_r($token);
        die();

        if ($token && $token->check()) {
            // El token es válido y no ha expirado
        } else {
            // El token es inválido o ha expirado
        }
    }

    public function login(Request $request){
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $vaidado = "";

        if($user->email_verified_at == null){
            //$token ="";
            $token = $user->createToken('authToken')->plainTextToken;
            $validado ="Necesitas verificar tu correo electrónico para iniciar sesión";
        }else{
            $token = $user->createToken('authToken')->plainTextToken;
            $validado = true;
        }


    
        return response()->json(
        [
        'token' => $token,
        "response"=>"success",
        "usuario" => $user->id,
        "nombre"=>$request->nombre,
        "username"=>$user->username,
        "validado"=>$validado,
        ], 
        200);
    } else {
        return response()->json(['validado' => 'Datos incorrectos'], 401);
    }


     
        $validator = Validator::make($request->all(), [
            'email'=>'required|email',
            'password'=>'required',
          
        ]);
      
            if ($validator->fails()) {
                return response()->json([
                    "response"=>$validator->messages(),
                ]);

            }else{
                $user = User::where('email', $request->email)->first();
                if (! $user || ! Hash::check($request->password, $user->password)) {
                    return response()->json([
                        "response"=>"Los datos proporcionados son inválidos",
                        "token"=>"",
                    ]);
                }else{

                  
                    // $token = $user->createToken('myapptoken')->plainTextToken;  
                    //laravel 
                    Auth::login($user);

                    //AQUI SERÍA GUARDAR EL TOKEN.
                    /*
                    $op=DB::table('personal_access_tokens')
                    ->insert([
                        'tokenable_id'=>$user->id,
                        'token'=>$token,
                    ]);*/

                    return response()->json([
                        "response"=>"success",
                        "usuario" => $user->id,
                        "username"=>$user->username,
                        "token" => $token,
                    ]);
                }
            }

    }
    public function logout(Request $request){
        //print_r(auth()->user());
        //wrong way
        $token = $request->token;
        $op=DB::table('personal_access_tokens')
        ->where('tokenable_id',$request->token)
        ->delete();
        $user = User::where('id', $request->token)->first();
        $user->tokens()->delete();
        Auth::logout();
        if($op){
            return response()->json([
                'response'=>"Sesión cerrada correctamente",
                'token'=>$token,
                'code'=>200,
                
            ]);
        }else{
            return response()->json([
                'response'=>"La sesión no se ha podido cerrarse correctamente",
                'code'=>400,
                'token'=>$token
                
            ]);
        }

       
    }

    public function verifyCaptcha($input){
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' =>$input,
            ]
        ]);
    
        $body = json_decode((string)$response->getBody());
        if (!$body->success) {
            // el reCAPTCHA no se validó correctamente
            return response()->json([
                "response"=>"Por favor, complete el reCAPTCHA correctamente.",
                "code"=>400,
            ]);
        }
    }

    public function delImgProfile(Request $request){
      

        $id_user = auth()->user()->id;
        $user = User::where('id',$id_user)->first();

   

        if($user->imagen !=null){
         
            $storage = new StorageClient();
            $bucket = $storage->bucket('findall_bucket');
            $object = $bucket->object($user->imagen);
            $object->delete();
            User::where('id',$id_user)
            ->update([
              'imagen'=>null,
            ]);
            return response()->json([
                "response"=>"Imagen de perfil eliminada",
                "code"=>200,
            ]);
        }

    }

    public function upImgProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'imagen'=>'required|image',
        ]);
        if ($validator->fails()) {
                return response()->json([
                    'coder'=>"400",
                    "response"=>$validator->messages(),
                ]);
        }
        $file = $request->file('imagen');
      
        if($file!=null){
            $id_user = auth()->user()->id;
            $user = User::where('id',$id_user)->first();
            $user_img = $user->imagen;

            $storage = new StorageClient();
            $bucket = $storage->bucket("findall_bucket");
            $name = "imagen_perfil_" . $id_user . "." . $file->getClientOriginalExtension();

            if($user_img==null){
                $object = $bucket->upload(file_get_contents($file), [
                    'name' => $name
              ]);
              User::where('id',$id_user)
              ->update([
                'imagen'=>$name,
              ]);
              return response()->json([
                'coder'=>"200",
                "response"=>"Imagen guardada con exito"
            ]);
            }else{
             
                $file_e = $bucket->object($name);

                if($file_e){
                    $object = $bucket->upload(file_get_contents($file), [
                        'name' => $name
                  ]);
                  return response()->json([
                    'coder'=>"200",
                    "response"=>"Imagen actualizada con exito"
                ]);

                }
                User::where('id',$id_user)
                ->update([
                  'imagen'=>$name,
                ]);
            }

        
         
            
           
            
            //antes de guardar necesita checar si ya existe, si ya existe actualizarla.


           
         
          }

    }

    public function setNewPass(Request $request){

        $validation = Validator::make($request->all(),[
            "token_r"=>"required",
            "contra"=>"required|min:8",
            "nuevaconfirmar"=>"required|same:contra"

        ]);

    
        if($validation->fails()){
            return redirect()->back()->with("error",$validation->messages());
        }else{
            $user = User::where('email_confirmation_token',$request->token_r)->first();
            if(!empty($user)){
                User::where('email_confirmation_token',$request->token_r)
                ->update([
                    "password"=>bcrypt($request->contra),
                    "email_confirmation_token" =>NULL,
                ]);
                return redirect()->route('login')->with("success","Contraseña actualizada, incia sesión con tu nueva contraseña");
            }else{
                return redirect()->back()->with("error","El token no existe");
            }
        }

    }

    public function newPass($token=null){
        if($token==null){
            return redirect()->route('/');
        }else{
            $user = User::where('email_confirmation_token',$token)->first();

            if($user==null){
                return redirect()->route('/');
            }else{
                return view('new-pass',["token_r"=>$token]);
            }

          
        }
     
    }

    public function recoveryPass(Request $request){

        $token =random_bytes(20);
        $token = bin2hex($token);
     
        $user = User::where('email',$request->email)->first();
      
        if(!empty($user) ){
            //enviar correo
            Mail::to($user->email)->send(new RecoveryPass($token));
            User::where('email',$request->email)
            ->update([
                'email_confirmation_token'=>$token,
            ]);
        }
        return redirect()->back()->with('response','Se ha enviado el correo de recuperación');
    }


    public function GetUserInfo($user_id){

        $user_info = UserInfo::with('user')->where('user', $user_id)->first();
        // Convertir a array y agregar el email directamente
       
        
        return response()->json($user_info);

    }

    public function UpdateUserInfo(Request $request){

      //  $userinfo = $userinfo->validated();
      $user = UserInfo::updateOrCreate(
        ['user' => $request->input('user.id')], // Look for record by 'id'
        $request->only([                   // Safely pull only desired fields
            'nombre',
            'ap',
            'am',
            'direccion',
            'ciudad',
            'estado',
            'cp',
            'public_info',
            'telefono',

        ])
    );

        if($user){
            return response()->json([
                "code"=>200,
                "user"=>$user,
                "message" =>"Actualizado con exito",
            ]);
        }else{
            return response()->json([
                "code"=>400,
                "error" =>"Actualizado con exito",
            ]);
        }

    }

    public function actualizarPerfil(Request $request){
        if($request->id !=null){
            $user=  User::
            where('id',$request->id)
            ->update([
                "nombre"=>$request->nombre,
                "email"=>$request->email,
                "ap"=>$request->ap,
                "am"=>$request->am,
                "direccion"=>$request->direccion,
                "ciudad"=>$request->ciudad,
                "estado"=>$request->estado,
                "cp"=>$request->cp,
                "telefono"=>$request->telefono,
                "public_info"=>$request->public_info
                ]);
                    if($user){
                    return response()->json([
                        "response"=>"Actualizado con éxito",
                        "code"=>200,
                    ]);
                            
                    }else{
                        return response()->json([
                            "response"=>"Ha ocurrido un error al actualizar",
                            "code"=>400,
                        ]);
                    }
        }
    }

    

    public function registro(Request $request){

        $input = $request->input('g-recaptcha-response');
        $this->verifyCaptcha($input);

        $validator = Validator::make($request->all(), [
            'username' => 'required|no_spaces|unique:users|min:5',
            // 'nombre'=>'required',
            // 'ap'=>'required',
            // 'am'=>'required',
            // 'direccion'=>'required',
            // 'ciudad'=>'required',
            // 'estado'=>'required',
            // 'cp'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'confirmpass' => 'required|same:password',
          
        ],
        [
            'username.no_spaces'=>"El nombre de usuario no debe contener espacios",
            'email.email'=>"El email debe ser un email valido",
            'password.min'=>"La contraseña debe tener al menos 8 caracteres",

        ]
    
        );
            if ($validator->fails()) {
                return response()->json([
                    'code'=>400,
                    "response"=>$validator->messages(),
                ]);
            }else{
                DB::beginTransaction();
                try {
                    $user=  User::create([
                        "username"=>$request->username,
                        "email"=>$request->email,
                        "password"=>bcrypt($request->password),
                        "user_type"=>"normal",
                        'email_confirmation_token' => Str::random(60),
                        
                        ]);

                        //return response()->json(["user" => $user]);
    
                        if($user){

                            UserInfo::create([
                            "nombre"=>$request->nombre,
                            "ap"=>$request->ap,
                            "am"=>$request->am,
                            "direccion"=>$request->direccion,
                            "ciudad"=>$request->ciudad,
                            "estado"=>$request->estado,
                            "cp"=>$request->cp,
                            "telefono"=>$request->telefono,
                            "user"=>$user->id
                            ]);
    
    
                            $token = $user->createToken('myapptoken')->plainTextToken;  
                            // Auth::login($user);
                            Mail::to($user->email)->send(new ConfirmEmail($user));
                            DB::commit();

                            return response()->json([
                                "response"=>"success",
                                "code"=>200,
                                "usuario" => $user->id,
                                "username"=>$user->username,
                                "token" => $token,
                                "validado"=>"Deberás confirmar tu correo electrónico para iniciar sesión",
                                "nombre"=>$request->nombre,
                            ]);

                        
                        
                        }
                      
                } catch (\Exception $th) {
                   DB::rollback();
                   return response()->json([
                    "response"=>"fails",
                    "m"=>$th->getMessage(),
                  
                ]);
                }
          
              
            

             
                //$token = $user->createToken('myapptoken')->plainTextToken;

                // return response()->json([
                //     "response"=>"success",
                //     "usuario" => $user,
                //   //  "token" => $token,
                // ]);
            }

      
    }
    public function confirmEmail(Request $request, $token)
    {
        $user = User::where('email_confirmation_token', $token)->firstOrFail();

        $user->email_verified_at = now();
        $user->email_confirmation_token = null;
        $user->save();

        
        $notificacion = NotiUser::create([
            "descripcion"=>"¡Completa la información de tu perfil!",
            "tipo"=>"system",
            "destino_user"=>$user->id,
        ]);

        return redirect()->route('login')->with('success', 'Tu correo electrónico ha sido confirmado correctamente.');
    }


    function validate_log(){

    }

    public function uptest(Request $request){
        $objectName="test.jpg";

        $storage = new StorageClient();
        $bucket = $storage->bucket('findall_bucket');
        $object = $bucket->object($objectName);
        $object->delete();


        die();
        $file = $request->file('image');

     
        $storage = new StorageClient();
        #$file = fopen($source, 'r');
        $bucket = $storage->bucket("findall_bucket");
        $object = $bucket->upload(file_get_contents($file), [
            'name' => $objectName
        ]);
        //printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($source), $bucketName, $objectName);
    }

  function miPDF(){
    $dompdf = new Dompdf();
    $dompdf->loadHtml('hello world');
    
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
    
    // Render the HTML as PDF
    $dompdf->render();
    
    // Output the generated PDF to Browser
    $dompdf->stream();
  }
    
}
