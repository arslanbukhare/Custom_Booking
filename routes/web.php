<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Booking_Controller;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking', [Booking_Controller::class, 'showBookingForm']);