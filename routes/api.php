<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Models\Permiso;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\PersonalApoyo;
use App\Models\PersonalCco;
use App\Models\SolicitudEmergencia;
use App\Models\TipoApoyo;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function (Request $request) {
    return 'pong';
});

Route::post('/registrar-usuario', function (Request $request) {
    //nombres,primer_apellido,segundo_apellido,genero,ci,ci_exp,celular,direccion,correo
    $persona = new Persona();
    
    $persona->nombres = $request->nombres;
    $persona->primer_apellido = $request->primer_apellido;
    $persona->segundo_apellido = $request->segundo_apellido;
    $persona->ci=$request->ci;
    $persona->ci_exp=$request->ci_exp;
    $persona->genero=$request->genero;
    $persona->celular = $request->celular;
    $persona->direccion=$request->direccion;
    $persona->correo=$request->correo; // validar si el correo existe

    $tipo=$persona->tipo = $request->tipo;
    
    // validar correo, nombre, primer_apellido, ci
    $persona->save();

    if($tipo=='cliente'){
        $cliente = new Cliente();
        $cliente->id=$persona->id;
        $cliente->login=$persona->ci;
        $cliente->pass = md5($persona->ci);
        $cliente->save();
    }else if($tipo=='cco'){
        $personalCco = new PersonalCco();
        $personalCco->id=$persona->id;
        $personalCco->login=$persona->ci;
        $personalCco->pass = md5($persona->ci);
        $personalCco->save();
    }else if($tipo=='apoyo'){
        $personalApoyo = new PersonalApoyo();
        $personalApoyo->id=$persona->id;
        $personalApoyo->login=$persona->ci;
        $personalApoyo->pass = md5($persona->ci);
        $personalApoyo->save();
    }

    

    $nombre_completo = "$persona->nombres $request->primer_apellido $request->segundo_apellido";
    $respuesta =['success'=>true,'id'=>$persona->id,'login'=>$persona->ci,'nombre'=>$nombre_completo];
    return response($respuesta, 200)->header('Content-Type', 'application/json');
    
});



Route::post('/autentificar', function (Request $request) { //Revisar

    $app = $request->app;
    $login = $request->login;
    $password =  md5($request->password) ;

    if($app=='cliente'){
        $cliente = new Cliente();
        $usuario = $cliente->obtenerUsuarioCliente($login, $password); // tabla cliente

    }else if($app=='cco'){
        $cco = new PersonalCco();
        $usuario = $cco->obtenerUsuarioCco($login, $password); // tabla Cco

    }else if($app=='apoyo'){
        $apoyo = new PersonalApoyo();
        $usuario = $apoyo->obtenerUsuarioApoyo($login, $password); // tabla Apoyo
    }
    
    
    
    if ($usuario) {
        $respuesta =['success'=>true,'tipo'=>$usuario->tipo,'id'=>$usuario->id,'login'=>$usuario->login,'nombre'=>$usuario->nombre_completo];
        return response($respuesta, 200)->header('Content-Type', 'application/json');
    } else {
        $respuesta=['success'=>false,"mensaje"=>"usuario de tipo $app no encontrado"];
        return response($respuesta, 200)->header('Content-Type', 'application/json');
    }
});

Route::post('/send/solicitud_emergencia', function (Request $request){
    
    $solicitud_emergencia = new SolicitudEmergencia();
     $solicitud_emergencia-> estado= 'En revision';
    //$solicitud_emergencia-> estado= $request->estado;
    $solicitud_emergencia-> ubicacion= $request->ubicacion;
    $solicitud_emergencia-> tipo_apoyo= $request->tipo_apoyo ;
    $solicitud_emergencia-> cliente_id= $request->cliente_id ;
  
    $solicitud_emergencia->save();

    return response("Solicitud enviada con exito", 200)->header('Content-Type', 'application/json');


});

Route::get('listado/solicitud_emergencia_cco',function (Request $request){
    $solicitud_emergencia = new SolicitudEmergencia();
    $solicitudes = $solicitud_emergencia->listado_solicitud_emergencias ();
    $response = [
        'succes'=> true,
        'solicitudes_e' =>$solicitudes
    ];
    
    return response($response, 200)->header('Content-Type', 'application/json');
});
Route::get('listado/personal_apoyo',function (Request $request){
    $personal_apoyo= new PersonalApoyo();
    $listado= $personal_apoyo->listadoPersonalApoyo();
    $response =[
        'success' => true,
        'listadopersonal'=>$listado
    ];
    return response($response, 200)->header('Content-Type', 'application/json');
});
Route::post('/assign_emergency_support',function(Request $request){

    $solicitud_emergencia = new SolicitudEmergencia();
     $solicitud_emergencia-> estado= 'En revision';
    //$solicitud_emergencia-> estado= $request->estado;
    $solicitud_emergencia-> ubicacion= $request->ubicacion;
    $solicitud_emergencia-> tipo_apoyo= $request->tipo_apoyo ;
    $solicitud_emergencia-> cliente_id= $request->cliente_id ;
    
    $solicitud_emergencia-> personal_cco_id= $request-> personal_cco_id;
    $solicitud_emergencia-> personal_apoyo_id= $request-> personal_apoyo_id;
  
    $solicitud_emergencia->save();

    return response("Solicitud enviada con exito", 200)->header('Content-Type', 'application/json');
});

Route::post('/registrar_tipos_apoyo',function(Request $request){
    $tipo = new TipoApoyo();
    $tipo -> nombre = $request->nombre;
    $tipo -> correo = $request->correo;
    $tipo -> especialidad  = $request->especialidad;
    $tipo -> grupo = $request->grupo;
    $tipo -> cargo = $request->cargo;

    $tipo->save();
    return response("Tipos Apoyo Registrado con exito", 200)->header('Content-Type', 'application/json');
});