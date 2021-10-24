<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Usuario extends Model
{
    use HasFactory;
    protected $table="usuarios";

    
    public function obtenerUsuarios($buscar='', $pagina=1)
    {
        $limite=15;
        $pagina = $pagina ? $pagina : 1;//no modificar (inicia la pagina)
        $inicio =  ($pagina -1)* $limite; // define el inicio

        $filtro="";
        if ($buscar!="") {
            $filtro=" and (a.nombre like '%$buscar%' ) ";
        }

        $sql = "select u.*,p.nombre as perfil, concat(pe.primer_apellido,' ',coalesce(pe.segundo_apellido,''),' ',pe.nombres) as persona 
                    from usuarios u
                    inner join personas pe on pe.id=u.id
                    left join perfiles p on p.id=u.perfil_id
                    where u.eliminado =0 $filtro order by id desc limit $inicio,$limite";
        $usuarios = DB::select($sql);

        $sqlTotal = "select count(*) as total from usuarios u
                    where u.eliminado = 0 $filtro";
        $result = DB::select($sqlTotal); //no tocar 
        $total=$totPaginas=0;
        if (count($result)>0) {
            $total=$result[0]->total;
            $totPaginas = round($total/$limite, 2)>floor($total/$limite)?floor($total/$limite)+1:floor($total/$limite);
        } //hasta aqui
        return [
            'usuarios'=>$usuarios,
            'total'=>$total, //NT
            'parPaginacion'=>['pagActual'=>$pagina,'totPaginas'=>$totPaginas] //NT
        ];
    }

    public function buscarPersonas($buscar){
        $sql="select id,concat(p.primer_apellido,' ',coalesce(p.segundo_apellido,''),' ',p.nombres) as nombre  from personas p 
            where concat(p.primer_apellido,' ',coalesce(p.segundo_apellido,''),' ',p.nombres) like '%$buscar%' and p.eliminado =0
            and not exists (select u.id from usuarios u where u.id=p.id )";

        $personas = DB::select($sql);
        return $personas;
    }

    public Static function getUsuario($id){
        $sql="select u.*,p.nombre as perfil, concat(pe.primer_apellido,' ',coalesce(pe.segundo_apellido,''),' ',pe.nombres) as persona 
        from usuarios u 
        left join perfiles p on p.id =u.perfil_id
        inner join personas pe on pe.id=u.id
        where u.id=$id";

        $usuarios= DB::select($sql);

        if(count($usuarios)>0){
            return $usuarios[0];
        }else{
            return null;
        }
        
    }
    //no

}
