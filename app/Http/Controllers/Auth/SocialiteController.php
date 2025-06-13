<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Hash;
use Str;
use DB;
use App\Models\NotiUser;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
      
        // aquí manejas la respuesta de Google y creas el usuario en tu aplicación
            $existingUser = User::where('email', $user->email)->first();
            if ($existingUser) {
                Auth::login($existingUser, true);              
                $token = $existingUser->createToken('myapptoken')->plainTextToken;  
                return view('registro', 
                [
                    'usuario' => $existingUser->id,
                    'username' => $existingUser->username,
                    'token' => $token,
                ]);
            } else {
                DB::beginTransaction();
                try {
                    $newUser=  User::create([
                        "username"=>$user->name,
                        "email"=>$user->email,
                        "password"=>Hash::make(Str::random(20)),
                        "user_type"=>"normal",
                        'email_verified_at' => now(),
                        ]);
                    
    
                    $notificacion = NotiUser::create([
                        "descripcion"=>"¡Completa la información de tu perfil!",
                        "tipo"=>"system",
                        "destino_user"=>$newUser->id,
                    ]);
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollback();
                }
               

                Auth::login($newUser, true);
                
            }

            $token = $newUser->createToken('myapptoken')->plainTextToken;  
            return view('registro', 
            [
                'usuario' => $newUser->id,
                'username' => $newUser->username,
                'token' => $token,
            ]);
            // return redirect('/dashboard');
    }
}
