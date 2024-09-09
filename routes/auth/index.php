<?php

use App\Http\Controllers\Auth\SignInController;
use Illuminate\Support\Facades\Route;



Route::post('/process',[SignInController::class,'process'])->name('process');
