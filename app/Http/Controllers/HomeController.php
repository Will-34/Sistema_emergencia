<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public $parControl=[
        'titulo' =>'Home',
    ];
    public function __invoke() {

        return view('home',['parControl'=>$this->parControl]);
    }
    /*public function __invoke() {
        $sql = "select * from mesa_examinadora_v_2020";
        $mesas = DB::select($sql);
        //nro,sigla,gr,materia,cod,docente,dia,fecha,hora,aula,modalidad,obs
        $cabeceras=[
                    ['columna'=>'nro','label'=>'NRO'],
                    ['columna'=>'sigla','label'=>'SIGLA'],
                    ['columna'=>'gr','label'=>'GR'],
                    ['columna'=>'materia','label'=>'MATERIA'],
                    ['columna'=>'cod','label'=>'COD'],
                    ['columna'=>'docente','label'=>'DOCENTE'],
                    ['columna'=>'dia','label'=>'DIA'],
                    ['columna'=>'fecha','label'=>'FECHA'],
                    ['columna'=>'hora','label'=>'HORA'],
                    ['columna'=>'aula','label'=>'AULA'],
                    ['columna'=>'modalidad','label'=>'MODALIDAD'],
                    ['columna'=>'obs','label'=>'OBS'],
                ];
        return view('reporte_mesa',['parControl'=>$this->parControl,'mesas'=>$mesas,'cabeceras'=>$cabeceras]);
    }
    */
}
