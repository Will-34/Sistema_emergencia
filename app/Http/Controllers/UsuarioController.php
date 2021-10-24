<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Persona;
use App\Models\Perfil;
use App\Libs\Funciones;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public $parControl=[
        'modulo'=>'seguridad',
        'funcionalidad'=>'usuarios',
        'titulo' =>'Usuarios',
    ];

    public function index(Request $request)
    {
        $usuario = new Usuario();
        $buscar=$request->buscar;
        $pagina=$request->pagina;
        $resultado = $usuario->obtenerUsuarios($buscar,$pagina);
        $mergeData = [
            'usuarios'=>$resultado['usuarios'],
            'total'=>$resultado['total'],
            'buscar'=>$buscar,
            'parPaginacion'=>$resultado['parPaginacion'],
            'parControl'=>$this->parControl
        ];
        return view('usuarios.index',$mergeData);
    }
    
    public function mostrar($id)
    {
        $usuario = Usuario::getUsuario($id);
        $perfil= new Perfil();
        $perfiles = $perfil->obtenerPerfilesActivos();

        $mergeData = ['id'=>$id,'usuario'=>$usuario,'perfiles'=>$perfiles,'parControl'=>$this->parControl];
        return view('usuarios.mostrar',$mergeData);
    }

    public function agregar()
    { 
        $perfil= new Perfil();
        $perfiles = $perfil->obtenerPerfilesActivos();

        $mergeData = ['parControl'=>$this->parControl,'perfiles'=>$perfiles];
        return view('usuarios.agregar',$mergeData);  
    }

    public function insertar(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'login'=>'required|max:30',
            'pass'=>'required|max:30',
            'perfil_id'=>'required',
            'activo'=>'required',
        ]);

        $usuario = new Usuario();
        $usuario->id = $request->id;
        $usuario->login = $request->login;
        $usuario->pass = md5($request->pass);
        $usuario->perfil_id = $request->perfil_id;

        $usuario->activo = $request->activo?true:false;
        $usuario->save();

        return redirect()->route('usuarios.mostrar',$request->id);
    }

    public function modificar($id)
    {
        $usuario = Usuario::find($id);
        $perfil= new Perfil();
        $perfiles = $perfil->obtenerPerfilesActivos();

        $oPersona = new Persona();
        $nombrePersona = $oPersona->getNombreCompleto($id);

        $mergeData = ['id'=>$id,'usuario'=>$usuario,'nombreCompleto'=>$nombrePersona,'perfiles'=>$perfiles,'parControl'=>$this->parControl];
        return view('usuarios.modificar',$mergeData);
    }

    public function actualizar(Request $request, Usuario $usuario)
    {
        $request->validate([
            'login'=>'required|max:30',
            'perfil_id'=>'required',
            'activo'=>'required',
        ]);
        $usuario->login = $request->login;
        if(trim($request->pass)!=''){
            $usuario->pass = md5($request->pass);
        }
        $usuario->perfil_id = $request->perfil_id;
        $usuario->activo = $request->activo?true:false;
        $usuario->save();

        return redirect()->route('usuarios.mostrar',$usuario->id);
    }

    public function eliminar($id)
    {
        $usuario = Usuario::find($id);
        $usuario->eliminado=true;
        $usuario->save();
        return redirect()->route('usuarios.index');
    }

    public function personasActivas(Request $request)
    {
        $buscar=$request->q;
        $usuario = new Usuario();
        $personas = $usuario->buscarPersonas($buscar);
        $resultado=[];
        foreach ($personas as $persona){
            $resultado[]=(object)['name'=>$persona->nombre,'id'=>$persona->id];
        }
        return json_encode($resultado);
    }
}
