<?php
function fecha_latina($fecha) {
    if ($fecha != '') {
        $afecha = explode(' ', $fecha);
        $hora = "";
        if(count($afecha)>1){
            $hora = $afecha[1];
        }
        $mifecha = explode('-', $afecha[0]);
        $lafecha = $mifecha[2] . "/" . $mifecha[1] . "/" . $mifecha[0];
        if($hora){
            $lafecha="$lafecha $hora";
        }
        return $lafecha;
    } else {
        return '';
    }
}

function fecha_iso($fecha) {
    if ($fecha != '') {
        $afecha = explode(' ', $fecha);
        $hora = "";
        if(count($afecha)>1){
            $hora = $afecha[1];
        }
        $mifecha = explode('/', $afecha[0]);
        $lafecha = $mifecha[2] . "-" . $mifecha[1] . "-" . $mifecha[0];
        if($hora){
            $lafecha="$lafecha $hora";
        }
        return $lafecha;
    } else {
        return '';
    }
}

function hora_minuto($hora){
    return Str::substr($hora, 0 , 5);
}

function paginacion($parPaginacion){
    return view('paginacion',['parPaginacion'=>$parPaginacion]);
}

function strMes($mes){
    $meses = [
        '1'=>array('nombre'=>'Enero'),
        '2'=>array('nombre'=>'Febrero'),
        '3'=>array('nombre'=>'Marzo'),
        '4'=>array('nombre'=>'Abril'),
        '5'=>array('nombre'=>'Mayo'),
        '6'=>array('nombre'=>'Junio'),
        '7'=>array('nombre'=>'Julio'),
        '8'=>array('nombre'=>'Agosto'),
        '9'=>array('nombre'=>'Septiembre'),
        '10'=>array('nombre'=>'Octubre'),
        '11'=>array('nombre'=>'Noviembre'),
        '12'=>array('nombre'=>'Diciembre'),
    ];
    return $meses[$mes]['nombre'];

}