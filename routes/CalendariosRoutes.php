<?php
use App\Http\Controllers\CalendarioController;

Route::get('calendario', [CalendarioController::class,'index'])->name('calendarios.index');