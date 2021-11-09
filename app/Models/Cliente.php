<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    use HasFactory;
    protected $table="clientes";

    public function obtenerUsuarioCliente($login, $password)
    { //Hace un join entre las tablas persona y cliente por sus Id,s
        $sql = "select concat(coalesce(p.primer_apellido,''),' ',coalesce(p.segundo_apellido,''),' ',p.nombres) as nombre_completo, c.login ,c.id       
        from clientes c 
       inner join personas p on p.id = c.id 
       where c.login =? and   c.pass=?
       and c.activo =1 and c.eliminado =0"; //Deja en null safety los parametros que vayamos a enviar por flutter
        $usuarios = DB::select($sql, [$login,$password]);
        if (count($usuarios)>0) {
            return $usuarios[0];
        } else {
            return null;
        }
    }
}
