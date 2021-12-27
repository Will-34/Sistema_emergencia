<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SolicitudEmergencia extends Model
{
    use HasFactory;
    protected $table="solicitud_emergencias";
}
