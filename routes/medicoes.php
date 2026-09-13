<?php

use App\Http\Controllers\Api\MedicaoController;
use Illuminate\Support\Facades\Route;

Route::post('/Apidados', [MedicaoController::class, 'store']);
