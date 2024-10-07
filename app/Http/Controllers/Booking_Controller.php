<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Booking_Controller extends Controller
{
    public function showBookingForm()
    {
        return view('booking');
    }
}
