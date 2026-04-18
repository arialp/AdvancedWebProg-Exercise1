<?php

use Illuminate\Support\Facades\Route;
use App\Classes\GraduateThesis;

Route::get('/', function () {
    $thesisManager = new GraduateThesis();
    $allTheses = $thesisManager->read();

    return response()->json($allTheses);
});