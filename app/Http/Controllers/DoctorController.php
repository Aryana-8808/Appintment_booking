<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    //returns the doctors profile
    public function main()
    {
        $doctors = Doctor::all(); // Fetch all doctors

        return view('doctor.index', compact('doctors'));
    }
}
