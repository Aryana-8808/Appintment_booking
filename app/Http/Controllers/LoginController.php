<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Include Auth facade for authentication
use Illuminate\Support\Facades\Hash;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Exception;

class LoginController extends Controller
{
    // this page will show login page for customer
    public function index()
    {
        return view('login');
    }

    // This method will authenticate user
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withErrors($validator) // Pass the entire validator object
                ->withInput(); // Preserve form input
        }


        // Attempt user authentication using Laravel's built-in Auth facade
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.dashboard');
        }else
        {
            return redirect()->route('account.login')->with('error', 'Either email or passwords is incorrect'); // Or desired route after login}
            // Authentication successful, redirect to intended location
            
        }

        // Authentication failed, return to login with error message
        return redirect()->route('account.login')
            ->withErrors(['login_failed' => 'Invalid email or password'])
            ->withInput();
    }

    //thius method will show register page

    public function register(){

        

        return view('register');

    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('account.register')
                ->withErrors($validator) // Pass the entire validator object
                ->withInput(); // Preserve form input
        }

            if($validator->passes()){
                $user = new User();
                $user->email = $request->email;
                $user->name = $request->name;
                $user->password = Hash::make ($request->password);
                $user->role = 'patient';
                $user->save();

                return redirect()->route('account.login')->with('success','You have registred Sucessfully! ');
            }
     
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('bookings.index');
    }

    public function syncWithGoogleCalendar(Request $request)
    {
        try {
            // Initialize Google Client
            $client = new GoogleClient();
            $client->setApplicationName('Your Application Name');
            $client->setScopes(Calendar::CALENDAR_EVENTS);
            $client->setAuthConfig(storage_path('app/calendar_credentials.json')); // Path to your credentials file
            $client->setAccessType('offline');

            // Check if access token is expired, refresh if needed
            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                file_put_contents(storage_path('app/calendar_credentials.json'), json_encode($client->getAccessToken()));
            }

            // Example: Fetch events from Google Calendar
            $service = new Calendar($client);
            $calendarId = 'primary'; // Use 'primary' for the primary calendar of the authenticated user
            $events = $service->events->listEvents($calendarId);

            // Process events as needed
            foreach ($events->getItems() as $event) {
                // Example: Process each event
                $eventSummary = $event->getSummary();
                // Handle event details as required
            }

            // Example: Redirect back to dashboard or specific route
            return redirect()->route('account.dashboard')->with('success', 'Google Calendar synchronized successfully.');

        } catch (Exception $e) {
            // Handle Google Calendar API exceptions
            return redirect()->route('account.dashboard')->with('error', 'Error syncing with Google Calendar: ' . $e->getMessage());
        }
    }


}
