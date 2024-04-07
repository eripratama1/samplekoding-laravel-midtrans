<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('order');
// });

Route::get('/',[\App\Http\Controllers\OrderController::class,'index'])->name('order');
Route::post('/payment',[\App\Http\Controllers\OrderController::class,'payment'])->name('payment');
Route::post('/notification',[\App\Http\Controllers\OrderController::class,'notificationHandler'])->name('notification');
