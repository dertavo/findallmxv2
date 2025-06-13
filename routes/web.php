<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\EntidadController;
use App\Http\Controllers\API\PruebasEntidadController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function ($user = null) {

   
    return view('home');
   
})
->name('/');

Route::get('politicas', function () {return view('politicas');})->name('politicas');

Route::get('acerca', function () {return view('acercade');})->name('acerca');



Route::get('google', function ($user = null) {
    return view('googleview');
})->name('google');


Route::get('/registro', function () {
    return view('registro');
    $user = auth()->user();
    if($user==null){
       
    }
   
})->name('registro');

Route::get('/login', function () {

    
    return view('login');
    
})
//->middleware('auth:sanctum')
->name('login');

Route::get('/recovery-pass',function(){
    return view('recovery-pass');
})->name('recovery');
//email
Route::post('/recoveryPass',[UserController::class,'recoveryPass'])->name('recoveryPass');

//validar si es token valido antes de la vista.
Route::get('/new-pass/{token?}',[UserController::class,'newPass']
)->name('new-pass');

Route::post('/setNewPass',[UserController::class,'setNewPass'])
->name('setNewPass');

Route::get('/example', function () {
    return view('example');
})->name('example');


Route::post('/server/upload', [EntidadController::class,'ex'])->name("ex");


Route::post('/buscar_entidad/', [EntidadController::class,'buscarNombreEntidad'])
->name("buscar_entidad");



/*
Route::get('/registro_entidad/{id?}', function ($id=null) {
    return view('form_entidad',['id'=>$id]);
})->name('registro_entidad');
*/

Route::get('/registro_entidad/{id?}', [EntidadController::class, 'viewEntidad'])
//->middleware('auth:sanctum')
->name('registro_entidad');


Route::get('/editar_entidad/{id?}', [EntidadController::class, 'viewEditarEntidad'])
//->middleware('auth:sanctum')
->name('editar_entidad');


// Route::get('/detalles_entidad/{entidad}', function ($entidad) {
//     return view('detalles_entidad');
// })->name('detalles_entidad');

Route::get('/detalles_entidad/{entidad}', [EntidadController::class, 'vista_detalles'])
//->middleware('auth:sanctum')
->name('detalles_entidad');





Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');


Route::get('registro/confirm-email/{token}', [UserController::class, 'confirmEmail'])
->name('registro.confirm-email');


//google


// Route::get('/login-google',function(){
//     return Socialite::driver('google')->redirect();
// });

// Route::get('/google-callback',function(){
//     $user =  Socialite::driver('google')->user();
// });

Route::get('/login/google', 'App\Http\Controllers\Auth\SocialiteController@redirectToGoogle')
->middleware('web')
->name('login.google');
Route::get('/login/google/callback', 'App\Http\Controllers\Auth\SocialiteController@handleGoogleCallback')
->middleware('web')
->name('login.callback');



Route::get('/mis_entidades', function () {
    return view('user.mis_entidades');
})
//->middleware('auth:sanctum')
->name('entidades');

// Route::get('/contacto_entidad/{entidad}', function ($entidad) {
//     return view('contacto_entidad',["entidad"=>$entidad]);
// })
// ->middleware('auth:sanctum')
// ->name('contacto_entidad');


Route::get('/contacto_entidad/{entidad}', [PruebasEntidadController::class, 'view'])
//->middleware('auth:sanctum')
->name('contacto_entidad');

Route::get('/contacts/{entidad}', [PruebasEntidadController::class, 'contacts'])->name('contacts');


Route::get('/accepted/{contact}', [PruebasEntidadController::class, 'acceptedContact'])->name('accepted');
Route::get('/declined/{contact}', [PruebasEntidadController::class, 'declinedContact'])->name('declined');







Route::get('/handshake/{contact}', [PruebasEntidadController::class, 'handShake'])->name('handshake');


Route::get('/finded/{user}', [PruebasEntidadController::class, 'finded'])
//->middleware('auth:sanctum')
->name('finded');


Route::get('/proweb',function(){
    return view("user.my-profile");
})
//->middleware('auth:sanctum')
->name('proweb');


Route::get('/contact-profile/{user}', [UserController::class, 'contactProfile'])->name('contact-profile');



Route::post('/chat', function (Request $request) {
   
    return view('chat',
    [
        'destino_user'=>$request->destino_user,
        'destino_username'=>$request->destino_username,
        'entidad_id'=>$request->entidad_id
    ]);
})->name('chat');


Route::get("/mipdf",[UserController::class, 'miPDF'])->name('mipdf');