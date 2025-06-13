<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\EntidadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PruebasEntidadController;
use App\Http\Controllers\API\NotiUserController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\NotificacionController;


use App\Http\Controllers\ControlPersonalController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::resource('personal',ControlPersonalController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    //Route::post('logout', [UserController::class,'logout'])->name('logout');
});




Route::get('getEntidadesUser/{user}', [EntidadController::class,'getEntidadesUser'])
->middleware(['auth:sanctum','verified'])
->name('getEntidadesUser');

Route::post('registro', [UserController::class,'registro'])
//->middleware('auth:sanctum')
->name('registro');

Route::post('actualizar-perfil', [UserController::class,'actualizarPerfil'])
->name('actualizar-perfil');




Route::post('login', [UserController::class,'login'])->name('login');


Route::post('logout', [UserController::class,'logout'])->name('logout');
Route::get('validate_log', [UserController::class,'validate_log'])->name('validate_log');



Route::get('validate_ver', function(){
})
->middleware(['auth:sanctum','verified'])
;

//Notifications.


Route::middleware('auth:sanctum')->post('/guardar-token', [NotificacionController::class, 'guardarToken']);

Route::post('/notificar', [NotificacionController::class, 'notificar']);


Route::post('contact_user', [PruebasEntidadController::class,'contactUser'])
->middleware('auth:sanctum')
->name('contact_user');

Route::post('update_user_info', [UserController::class,'UpdateUserInfo'])
->middleware('auth:sanctum')
->name('update_user_info');

Route::get('get_user_info/{user}',[UserController::class, 'GetUserInfo'])
->middleware('auth:sanctum')
->name('get_user_info');


Route::post('contact_userMovil', [PruebasEntidadController::class,'contactUserMovil'])
->middleware('auth:sanctum')
->name('contact_userMovil');



Route::get('/findedAPI/{user}', [PruebasEntidadController::class, 'findedAPI'])
->middleware('auth:sanctum')
->name('finded');


// Route::get('deleteUpdateImage/{entidad}', [EntidadController::class,'deleteUpdateImage'])
// //->middleware('auth:sanctum')
// ->name('deleteUpdateImage');


Route::post('deleteEntidad/{user}', [EntidadController::class,'deleteEntidad'])
->middleware('auth:sanctum')
->name('deleteEntidad');


Route::post('registro_entidad', [EntidadController::class,'registro'])
->middleware(['auth:sanctum','verified'])
->name('registro_entidad');

Route::post('registro_movil', [EntidadController::class,'registro_movil'])
->middleware(['auth:sanctum','verified'])
->name('registro_movil');


Route::get('/contactsMovil/{entidad}', [PruebasEntidadController::class, 'contactsMovil'])
->middleware('auth:sanctum')
->name('contactsMovil');




Route::put('/editar_entidad/{id}', [EntidadController::class,'editarEntidad'])
->middleware('auth:sanctum')
->name('editar_entidad');

Route::put('/editar_entidadMovil/{id}', [EntidadController::class,'editarEntidadMovil'])
->middleware('auth:sanctum')
->name('editar_entidadMovil');

Route::post('registro_img', [EntidadController::class,'registro_img'])
->middleware('auth:sanctum')
->name('registro_img');

Route::post('delImg', [EntidadController::class,'DelImgEntidad'])
->middleware('auth:sanctum')
->name('delImg');

Route::post('changeStatusEntidad', [EntidadController::class,'changeStatusEntidad'])
->middleware('auth:sanctum')
->name('changeStatusEntidad');


Route::get('delEvidence/{evidence}', [PruebasEntidadController::class,'delEvidence'])
->middleware('auth:sanctum')
->name('delEvidence');

Route::get('getUbicaciones/{lat}/{lng}', [EntidadController::class,'getUbicaciones'])->name('getUbicaciones');

Route::get('getUbicacionesMovil/{lat}/{lng}', [EntidadController::class,'getUbicacionesMovil'])->name('getUbicacionesMovil');

Route::get('detalles_entidadNormal/{entidad}', [EntidadController::class,'detalles_entidad'])
//->middleware('auth:sanctum')
->name('detalles_entidad');

Route::get('detalles_entidadNormal/{entidad}', [EntidadController::class,'detalles_entidadNormal'])
//->middleware('auth:sanctum')
->name('detalles_entidadNormal');

Route::get('detalles_entidad/{entidad}', [EntidadController::class,'detalles_entidad'])
->middleware('auth:sanctum')
->name('detalles_entidad');


Route::post('my-profile', [UserController::class,'myProfile'])
->middleware('auth:sanctum')
->name('my-profile');

Route::get('/mensaje', function () {
    return response()->json([
        'mensaje' => '¡Hola desde tu API de Laravel!'
    ]);
});

Route::post('up-imgprofile', [UserController::class,'upImgProfile'])
->middleware('auth:sanctum')
->name('up-imgprofile');

Route::post('del-imgprofile', [UserController::class,'delImgProfile'])
->middleware('auth:sanctum')
->name('del-imgprofile');




Route::get('get-noti/{user}', [NotiUserController::class,'read'])
->middleware('auth:sanctum')
->name('get-noti');

Route::get('del-noti/{user}', [NotiUserController::class,'delete'])
->middleware('auth:sanctum')
->name('del-noti');


Route::post('save-noti', [NotiUserController::class,'create'])
->middleware('auth:sanctum')
->name('save-noti');


Route::get('/acceptedMovil/{contact}', [PruebasEntidadController::class, 'acceptedContactMovil'])
->middleware('auth:sanctum')
->name('acceptedMovil');

Route::get('/declinedMovil/{contact}', [PruebasEntidadController::class, 'declinedContactMovil'])
->middleware('auth:sanctum')
->name('declinedMovil');

Route::get('/handshakeMovil/{contact}', [PruebasEntidadController::class, 'handShakeMovil'])
->middleware('auth:sanctum')
->name('handshakeMovil');



Route::get('get-messages/{user}/{contacto}/{entidad}', [ChatController::class,'read'])
->middleware('auth:sanctum')
->name('get-messages');


Route::post('save-message', [ChatController::class,'store'])
->middleware('auth:sanctum')
->name('save-message');


Route::post('change-pass', [UserController::class,'changePass'])

->name('change-pass');


Route::post('uptest', [UserController::class,'uptest'])
->name('uptest');


// Route::get('hola',[UserController::class,'hola'])
// //->middleware('auth:sanctum')
// ->name('hola');