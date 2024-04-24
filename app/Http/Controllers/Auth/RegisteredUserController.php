<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['string', 'lowercase', 'email', 'max:100'],
            'contact' => ['string',  'max:15'],
            'username' => ['required', 'string', 'lowercase', 'max:50', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $userprofile = UserProfile::create([
            'user_id' =>  $user->id,
            'email' => $request->email,
            'profile_type' => 1,
            'contact' => $request->contact,
            'is_default_profile' => 1,
        ]);

        event(new Registered($user));

       // Auth::login($user);

        //return redirect(RouteServiceProvider::HOME);
        return Redirect::route('login');
    }

    public function checkusername(Request $request)
    {
        $user = User::where('username',$request->username)->first();
        
        if($user){
            $data['message'] = true; 
        }
        else{
            $data['message'] = false; 
        }
        return Response::json($data);
        exit;
    }

    public function checkregcontact(Request $request)
    {
        $user = UserProfile::where('contact',$request->contact)->first();
        
        if($user){
            $data['message'] = true; 
        }
        else{
            $data['message'] = false; 
        }
        return Response::json($data);
        exit;
    }

    public function checkregemail(Request $request)
    {
        $user = UserProfile::where('email',$request->email)->first();
        
        if($user){
            $data['message'] = true; 
        }
        else{
            $data['message'] = false; 
        }
        return Response::json($data);
        exit;
    }
}
