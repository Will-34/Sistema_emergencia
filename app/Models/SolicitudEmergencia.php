<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitudEmergencia extends Model
{
    use HasFactory;
    protected $table="solicitud_emergencias";
    public function listado_solicitud_emergencias(){
        $sql = "select se.id ,concat(coalesce(p.primer_apellido,''),' ',coalesce(p.segundo_apellido,''),' ' , p.nombres) as nombre_completo, se.ubicacion ,p.direccion , se.tipo_apoyo , se.estado 
        from solicitud_emergencias se 
        inner join clientes c on  c.id = se.cliente_id 
        inner join personas p on p.id = c.id 
        order by se.id  desc ";
        $solicitudes_e = DB::select($sql);
        return $solicitudes_e;
    }
    public function solicitud_emergencias_apoyo($personal_apoyo_id){
        $sql ="select se.id , concat(coalesce(p.primer_apellido,''),' ',coalesce(p.segundo_apellido,''),' ' , p.nombres) as nombre_completo, se.ubicacion ,se.estado , se.tipo_apoyo 
        from solicitud_emergencias se
        inner join personal_apoyo pa  on pa.id = se.personal_apoyo_id 
        inner join clientes c on c.id = se.cliente_id 
        inner join personas p on p.id = c.id 
        where personal_apoyo_id =$personal_apoyo_id";
        $apoyo= DB::select($sql);
        return $apoyo;
    }
}
