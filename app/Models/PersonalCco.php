<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonalCco extends Model
{
    use HasFactory;
    protected $table="personal_cco";

    public function obtenerUsuarioCco ($login , $password){
        $sql= "select concat(coalesce(p.primer_apellido,''),' ',coalesce(p.segundo_apellido,''),' ',p.nombres) as nombre_completo,
		pc.login , pc.id     , p.tipo
        from personal_cco pc 
       inner join personas p on p.id = pc.id 
       where pc.login =? and pass=?
       and pc.activo =1 and pc.eliminado =0";
       $usuarios = DB::select($sql, [$login,$password]); 
       if(count($usuarios)>0){
       return $usuarios[0];
       }else{
       return null;
       } 
    }
}
