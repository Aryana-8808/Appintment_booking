<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
   //this method will show register page to admin
   public function index()
   {
    $appointments=Booking::all();
       return view('admin.AdminDashboard')->with(compact('appointments'));
   }
}
