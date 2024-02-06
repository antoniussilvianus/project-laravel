<?php
use Illuminate\Support\Facades\Route;


//route resource
Route::resource('/artikels', \App\Http\Controllers\ArtikelController::class);