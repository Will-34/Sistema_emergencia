<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Models\Permiso;
use App\Models\Persona;
use App\Models\Cliente;
use App\Models\PersonalApoyo;
use App\Models\PersonalCco;
use App\Models\SolicitudEmergencia;


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

Route::get('/empleados', function (Request $request) {
    $empleado = new Empleado();
    $buscar=$request->buscar;
    $pagina=$request->pagina;
    $resultado = $empleado->obtenerEmpleados($buscar, $pagina);
    $respuesta = [
         'empleados'=>$resultado['empleados'],
         'total'=>$resultado['total'],
         'parPaginacion'=>$resultado['parPaginacion'],
    ];
    return response($respuesta, 200)->header('Content-Type', 'application/json');
});

Route::get('/empleados/datos', function (Request $request) {
    $empleado = new Empleado();
    /// obtener empleado_id a partir del session_id
    $empleado_id=$request->empleado_id;

    if ($empleado_id) {
        $datos = $empleado->obtenerDatosEmpleado($empleado_id);
        $respuesta = [
            "id"=> $datos->id,
            "correo_corporativo"=> $datos->correo_corporativo,
            "profesion"=> $datos->profesion,
            "activo"=>$datos->activo,
            "persona"=> $datos->persona,
            "cargo"=> $datos->cargo,
            "sucursal"=> $datos->sucursal,
            "sueldo_basico"=> $datos->sueldo_basico,
            "fecha_inicio"=> $datos->fecha_inicio,
            "fecha_final"=> $datos->fecha_final,
        ];
        if ($datos) {
            return response($respuesta, 200)->header('Content-Type', 'application/json');
        } else {
            return response(null, 404)->header('Content-Type', 'application/json');
        }
    } else {
        return response("Ingrese el identificador del empleado", 409)->header('Content-Type', 'application/json');
    }
});


Route::get('/vacaciones/dias', function (Request $request) {
    $vacacion = new Vacacion();
    $empleado_id=$request->empleado_id;
    if ($empleado_id) {
        $resultado = $vacacion->obtenerDiasVacacionesEmpleado($empleado_id);
        $respuesta = [
                'dias_aprobados'=>$resultado,
                'dias_vacaciones'=>15
        ];
        return response($respuesta, 200)->header('Content-Type', 'application/json');
    } else {
        return response("Ingrese el identificador del empleado", 409)->header('Content-Type', 'application/json');
    }
});

Route::get('/vacaciones/dias-vacaciones', function (Request $request) {
    $vacacion = new Vacacion();
    $fecha_ini=$request->fecha_ini;
    $fecha_fin=$request->fecha_fin;
    $dias = $vacacion->CalcularDiasVacaciones($fecha_ini, $fecha_fin);
    return response($dias, 200)->header('Content-Type', 'application/json');
});
Route::get('/vacaciones/solicitudes', function (Request $request) {
    $vacacion = new Vacacion();
    $empleado_id=$request->empleado_id;
    $solVacaciones = $vacacion->getSolicitudesVacaciones($empleado_id);
    $respuesta = [
        'success'=>true,
        'vacaciones'=>$solVacaciones
    ];
    return response($respuesta, 200)->header('Content-Type', 'application/json');
});
Route::get('/boletas', function (Request $request) {
    $boleta = new Boleta();
    $empleado_id=$request->empleado_id;
    $boletas = $boleta->obtenerListadoBoletas($empleado_id);
    $respuesta = [
        'success'=>true,
        'boletas'=>$boletas
    ];
    return response($respuesta, 200)->header('Content-Type', 'application/json');
});

Route::post('/vacaciones/solicitud', function (Request $request) {
    $fecha_ini=$request->fecha_ini;
    $fecha_fin=$request->fecha_fin;
    $observacion=$request->observacion;
    $empleado_id=$request->empleado_id;

    $vacacion = new Vacacion();
    $dias = $vacacion->CalcularDiasVacaciones($fecha_ini, $fecha_fin);
    
    $solicitudVacacion = new SolicitudVacacion();
    $solicitudVacacion->fecha_ini=$fecha_ini;
    $solicitudVacacion->fecha_fin=$fecha_fin;
    $solicitudVacacion->observacion=$observacion;
    $solicitudVacacion->empleado_id=$empleado_id;
    $solicitudVacacion->dias=$dias;
    $solicitudVacacion->estado='PENDIENTE';
    $solicitudVacacion->activo=true;
    $solicitudVacacion->eliminado=false;
    $solicitudVacacion->save();

    return response("OK", 200)->header('Content-Type', 'application/json');
});


Route::get('/personas', function (Request $request) {
    $persona = new Persona();
    $buscar=$request->buscar;
    $pagina=$request->pagina;
    $resultado = $persona->obtenerPersonas($buscar, $pagina);
    $respuesta = [
         'personas'=>$resultado['personas'],
         'total'=>$resultado['total'],
         'parPaginacion'=>$resultado['parPaginacion'],
   ];
    return response($respuesta, 200)->header('Content-Type', 'application/json');
});

// Route::post('/listar-vacaciones', function (Request $request) {
//     $login =$request->login;
//     $pass =$request->pass;
//     if(existe) return '{"success":true}';
// });
