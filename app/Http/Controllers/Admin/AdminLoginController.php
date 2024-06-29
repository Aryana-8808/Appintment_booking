<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminLoginController extends Controller
{
    //this method will show admin login paeg
    public function index(){
        return view ('admin.AdminLogin');
    }

    //this method will authenticate admin
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.AdminLogin')
                ->withErrors($validator) // Pass the entire validator object
                ->withInput(); // Preserve form input
        }


        // Attempt user authentication using Laravel's built-in Auth facade
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // If authentication is successful, redirect to the admin dashboard
            if (Auth::guard('admin')->user()->role != "admin") {
                Auth::guard('admin')->logout();
                return redirect()->route('admin.AdminLogin')->with('error', 'You are not authorized to access this page.');
            }
            
            // If authentication is successful, redirect to the admin dashboard
            return redirect()->route('admin.AdminDashboard');
        }else
        {
            return redirect()->route('admin.AdminLogin')->with('error', 'Either email or passwords is incorrect'); // Or desired route after login}
            // Authentication successful, redirect to intended location
            
        }

        // Authentication failed, return to login with error message
        return redirect()->route('admin.AdminLogin')
            ->withErrors(['login_failed' => 'Invalid email or password'])
            ->withInput();
    }

    //this method will logout admin user and redirect to login page
    public function logout(){
         Auth::guard('admin')->logout();
         return redirect()->route('admin.AdminLogin');

    }
}