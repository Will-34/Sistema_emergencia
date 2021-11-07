<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PersonalApoyo extends Model
{
    use HasFactory;
    protected $table="personal_apoyo";

    public function obtenerUsuarioApoyo($login, $password){
      $sql=  " select concat(coalesce(p.primer_apellido,''),' ',coalesce(p.segundo_apellido,''),' ',p.nombres) as nombre_completo,
		pa.login , pa.id     
        from personal_apoyo pa 
       inner join personas p on p.id = pa.id 
       where pa.login =$login and pass=$password
       and pa.activo =1 and pa.eliminado =0";

       $usuarios = DB::select($sql, [$login,$password]); 
       if(count($usuarios)>0){
       return $usuarios[0];
       }else{
       return null;
       } 
    
    }
}
