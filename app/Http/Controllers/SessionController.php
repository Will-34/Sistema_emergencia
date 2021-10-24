<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permiso;
use App\Models\User;

use Illuminate\Support\Facades\DB;
//SessionController
class SessionController extends Controller
{
    public function iniciar(Request $request){
        $permiso = new Permiso();        
        $login = $request->login;
        $password =  md5($request->password) ;
        $tipo =  $request->tipo;
        
        $usuario = $permiso->obtenerUsuario($login,$password,$tipo);
        
        if($usuario){
            $permisos = $permiso->obtenerPermisosUsuario($usuario->perfil_id,$usuario->tipo);
            session(['auth'=>true,'id'=>$usuario->id,'login'=>$usuario->login,'nombre'=>$usuario->nombre_completo,'perfil_id'=>$usuario->perfil_id,'permisos'=>$permisos]);
            return redirect(route('index'));
        }else{
            return redirect(route('login'));    
        }
    }
    public function cerrar(){
        //Auth::login(null);
        session(['auth'=>false,'id'=>0,'login'=>'','nombre'=>'','perfil_id'=>0,'permisos'=>null]);
        return redirect(route('index'));
    }
}
