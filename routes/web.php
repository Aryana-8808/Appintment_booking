<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\BookingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminBookingsController;
use App\Http\Controllers\admin\AdminDashboardController;



Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix'=>'account'], function(){

    //guest middleware -> guest le access garna milne inteface ko route

    Route::group(['middleware'=> 'guest'], function(){
        Route::get('login',[LoginController::class,'index'])->name('account.login');
        Route::get('register',[LoginController::class,'register'])->name('account.register');
        Route::post('process-register',[LoginController::class,'processRegister'])->name('account.processRegister');
        Route::post('authenticate',[LoginController::class,'authenticate'])->name('account.authenticate');
    });

    //authenticated middleware
    Route::group(['middleware'=> 'auth'], function(){
        Route::get('logout',[LoginController::class,'logout'])->name('account.logout');
        Route::get('dashboard',[DashboardController::class,'index'])->name('account.dashboard');


    });
});

Route::group(['prefix'=>'admin'], function(){

    //guest middleware for admin=> unauthenticated admin access garene
    Route::group(['middleware'=> 'admin.guest'], function(){
        Route::get('login',[AdminLoginController::class,'index'])->name('admin.AdminLogin');
        Route::post('authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
        
    });

    //authenticated middleware for admin
    Route::group(['middleware'=> 'admin.auth'], function(){
        Route::get('dashboard',[AdminDashboardController::class,'index'])->name('admin.AdminDashboard');
        Route::get('logout',[AdminLoginController::class,'logout'])->name('admin.AdminLogout');


    });
});

Route::group(['prefix' => 'admin', 'middleware' => 'admin.auth'], function() {
    Route::get('/appointments', [AdminBookingsController::class, 'index'])->name('admin.dashboard');
    Route::get('/appointments/create', [AdminBookingsController::class, 'create'])->name('admin.create');
    Route::post('/appointments', [AdminBookingsController::class, 'store'])->name('admin.store');
    Route::get('/appointments/{id}/edit', [AdminBookingsController::class, 'edit'])->name('admin.appointments.edit');
    Route::put('/appointments/{id}', [AdminBookingsController::class, 'update'])->name('admin.update');
    Route::delete('/appointments/{id}', [AdminBookingsController::class, 'destroy'])->name('admin.appointments.destroy');
});


    // Routes for BookingsController
    Route::get('/bookings', [BookingsController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingsController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingsController::class, 'store'])->name('bookings.store');
    Route::get('/dashboard', [BookingsController::class, 'appointment'])->name('bookings.appointment');
    Route::get('/bookings/{id}/edit', [BookingsController::class, 'edit'])->name('appointment.edit');
    Route::put('/bookings/{appointment}', [BookingsController::class, 'update'])->name('appointment.update');
    Route::delete('/bookings/{appointment}', [BookingsController::class, 'destroy'])->name('appointment.destroy');
    Route::post('/waitlist', [BookingsController::class, 'waitlist'])->name('bookings.waitlist');
    
    Route::get('/doctors', [DoctorController::class, 'main'])->name('doctors.index');

    Route::get('/bookings/show', [BookingsController::class, 'show'])->name('bookings.show');

    Route::get('/sync/google/calendar', 'LoginController@syncWithGoogleCalendar')->name('sync.google.calendar');

    Route::get('/about', [BookingsController::class, 'about'])->name('aboutclinic');

    Route::get('/appointments/{id}/edit', [BookingsController::class, 'edit'])->name('appointment.edit');


    Route::put('/appointments/{id}/cancel', [BookingsController::class, 'cancelBooking'])->name('appointment.cancel');
    Route::get('waitlist/{id}/add', [WaitlistController::class, 'addToWaitlist'])->name('waitlist.add');

    Route::post('/waitlist/{id}/add', [BookingsController::class, 'addToWaitlist'])->name('waitlist.add');
    Route::put('/appointments/{id}/cancel', [BookingsController::class, 'cancelBooking'])->name('appointment.cancel');









    
