<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Mail; 
use Hash;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
        ]);

        //login user
        $user = User::where('username', $request->username)->first();
        if(!$user){
            return back()->with('message', 'Username not matching!');
        }
        $userprofile = UserProfile::where('user_id',$user->id)->where('is_default_profile',1)->first();
        //$credentials = array('email' => $userprofile->email);
        //dd($credentials);

        //login user
        $resetpass = DB::table('password_reset_tokens')->where('email',$userprofile->email)->first();
        if($resetpass){
            return back()->with('message', 'Reset password link already send. Please check your email!');
        }

        $token = Str::random(64);
  
        DB::table('password_reset_tokens')->insert([
            'email' => $userprofile->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
          ]);

        Mail::send('email.forgetPassword', ['token' => $token,'email' => $userprofile->email], function($message) use($userprofile){
            $message->to($userprofile->email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('message', 'Please check your mail for reset password link!');


        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //      $request->only('email')
        // );

        // return $status == Password::RESET_LINK_SENT
        //             ? back()->with('status', __($status))
        //             : back()->withInput( $request->only('email'))
        //                     ->withErrors(['email' => __($status)]);
    }
}
