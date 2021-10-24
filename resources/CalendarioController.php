<?php

namespace App\Http\Controllers;
use App\Libs\Funciones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarioController extends Controller
{
    public $parControl=[
        
        'titulo' =>'CALENDARIO ACADEMICO 2-2021 (FICCT)',
    ];

    public function index(Request $request)
    {

        $sql = "select * from calendario2021";
        $calendario = DB::select($sql);

        $mergeData = [
            'parControl'=>$this->parControl
        ];

        return view('calendarios.index',['parControl'=>$this->parControl,'calendario'=>$calendario, $mergeData]);
    }
}
    