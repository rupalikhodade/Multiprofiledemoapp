<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use DB;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'username' => trans('auth.failed'),
        //     ]);
        // }
        $input = $this->username;
        $users = User::orWhere('username', $input)
        ->orWhereHas('user_profiles', function ($query) use ($input) {
                $query->where('email', $input)
                    ->orWhere('contact', $input);
            })
            ->first();
        if(!$users || (!Hash::check($this->password, $users->password))){ 
            RateLimiter::clear($this->throttleKey());
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
            }

    //     $login = $this->username;
 
    //     $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? "email" : (filter_var($login, FILTER_SANITIZE_NUMBER_INT) ? "contact" : "username");
        
    //     // new login logic
    //    if($fieldType == "email" || $fieldType == "contact"){
    //     $user = UserProfile::where($fieldType, $login)->first();
    //    }else{
    //     $user = User::where('username', $login)->first();
    //    }
       
    //    if($fieldType == "email" || $fieldType == "contact"){
    //     if(!$user || (!Hash::check($this->password, $user->User->password))){ 
    //         RateLimiter::clear($this->throttleKey());
    //         throw ValidationException::withMessages([
    //             'username' => trans('auth.failed'),
    //         ]);
    //         }
    //     }
    //     else{
    //         if(!$user || (!Hash::check($this->password, $user->password))){ 
    //             RateLimiter::clear($this->throttleKey());
    //             throw ValidationException::withMessages([
    //                 'username' => trans('auth.failed'),
    //             ]);
    //             }
    //     }
        Auth::login($users,$this->boolean('remember'));


        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
}
