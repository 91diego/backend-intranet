<?php

use Illuminate\Http\Request;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

// RUTAS LOGIN JWT
Route::group([

    'middleware' => 'api'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('signup', 'AuthController@signup');
    Route::post('me', 'AuthController@me');

});

// RUTA AL CONTROLLER DE DESARROLLOS
Route::resource('desarrollos', 'DesarrolloController');
Route::get('desarrollos/details/{id}', 'DesarrolloController@details');

// RUTA AL CONTROLLER DE PROTOTIPOS
Route::resource('prototipos', 'PrototipoController');
Route::get('prototipos/details/{id}', 'PrototipoController@details');

// RUTA AL CONTROLLER DE PISOS
Route::resource('pisos', 'PisoController');
Route::get('pisos/details/{id}', 'PisoController@details');

// RUTA AL CONTROLLER PLUSVALIA
Route::resource('plusvalia', 'PlusvaliaController');

// RUTA AL CONTROLLER SUPUESTO DE COMPRA
Route::resource('supuesto-compra', 'SupuestoCompraController');
Route::get('supuesto-compra/details/{id}', 'SupuestoCompraController@details');

// RUTA AL CONTROLLER SUPUESTO HIPOTECARIO
Route::resource('supuesto-hipotecario', 'SupuestoHipotecarioController');
// Route::resource('supuesto-hipotecario/details/{id}', 'SupuestoHipotecarioController@details');

// RUTA AL CONTROLLER SUPUESTO DE MERCADO
Route::resource('supuesto-mercado', 'SupuestoMercadoController');
Route::get('supuesto-mercado/details/{id}', 'SupuestoMercadoController@details');

// RUTA AL CONTROLLER SUPUESTO DE OBRA
Route::resource('supuesto-obra', 'SupuestoObraController');
Route::get('supuesto-obra/details/{id}', 'SupuestoObraController@details');

// RUTA AL CONTROLLER SUPUESTO DE VENTA
Route::resource('supuesto-venta', 'SupuestoVentaController');
Route::get('supuesto-venta/details/{id}', 'SupuestoVentaController@details');

// RUTA AL CONTROLLER HOJA DE LLENADO
Route::resource('hoja-llenado', 'HojaLlenadoController');

// RUTA AL CONTROLLER EMPLEADO CONTPAQ
Route::resource('empleado', 'EmpleadoController');

/* RUTAS ACCESO CONTROL VACACIONES */

// CRUD CATALOGO MOTIVO PERMISOS
Route::resource('control-vacaciones/motivo-permisos', 'ControlVacaciones\CatalogoMotivoPermisosController');

// DEFINE EL ACCESO A LA APP
Route::resource('control-vacaciones', 'ControlVacaciones\ControlVacacionesController');
// CRUD CATALOGO DEPARTAMENTOS
Route::resource('departamentos-idex', 'ControlVacaciones\CatalogoDepartamentosController');
// INSERTA Y ACTUALIZA INFORMACION DE BITRIX A LA BD EXTERNA
Route::get('departamentos-idex/bdbitrix/{name}', 'ControlVacaciones\CatalogoDepartamentosController@datosBitrix');

// INSERTA Y ACTUALIZA INFORMACION DE BITRIX A LA BD EXTERNA
Route::get('usuarios-idex/bdbitrix/{name}', 'ControlVacaciones\UsuariosController@datosBitrix');

/* RUTA DE ACCESO A LA APLICACION */
Route::get('control-acceso/acceso/{id}/{workPosition}', 'ControlAccesoCalculadoraController@userAccess');

/* RUTA PARA ENVIO DE CORREOS */
Route::get('envio-mails-cobranza', 'EnvioMailsCobranza\SendMailCobranzaController@sendingMail');

/* RUTA INTEGRACION CHATBOT */
Route::get('bot/newLead/{name}/{lastName}/{phone}/{email}/{purchase}/{zone}', 'ChatBot\ChatBotController@newLead');
Route::resource('bot/newLead', 'ChatBot\ChatBotController');

/* INFORMACION DISPONIBILIDAD COTIZADOR */

// RUTA PARA OBTENER LA DISPONIBILIDAD DE LA TORRES
Route::resource('torres', 'Cotizador\CotizadorController');
Route::get('torre/{desarrollo}/{torre}', 'Cotizador\CotizadorController@torre');
Route::get('piso-general/{desarrollo}/{torre}', 'Cotizador\CotizadorController@pisoGeneral');
Route::get('piso-detallado/{desarrollo}/{torre}/{piso}', 'Cotizador\CotizadorController@pisoDetallado');
Route::get('departamento-detallado/{desarrollo}/{torre}/{piso}/{departamento}', 'Cotizador\CotizadorController@departamentoDetallado');
/* FIN INFORMACION DISPONIBILIDAD COTIZADOR */