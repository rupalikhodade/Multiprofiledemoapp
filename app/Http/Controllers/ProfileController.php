<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Response;

class ProfileController extends Controller
{
   
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {  
        $user = Auth::user();
        
        User::where('id', $user->id)->where('id',$user->id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

     /**
     * Display the user's personal profile form.
     */
    public function personal_profile_edit(Request $request): View
    {
        return view('profile.personal_profile_edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's personal profile edit form.
     */
    public function personal_profile_update(ProfileUpdateRequest $request): RedirectResponse
    {  
        define('USERS_PROFILE_FULL_UPLOAD_SHORT_PATH', 'public/images/full/');
        define('USERS_PROFILE_FULL_UPLOAD_PATH', storage_path() . '/app/public/images/full/');
        define('USERS_PROFILE_SMALL_UPLOAD_PATH',  storage_path() . '/app/public/images/small/');
        $user = Auth::user();
     
        $record = new UserProfile();
        $record->user_id = $user->id;
        $record->contact = $request->contact;
        $record->address = $request->address;
        $record->email = $request->email;
        
        if($request->image){
        
            $small_path = storage_path('images/small/');
            //$full_path = storage_path('images/full/');
            $file = $request->file('image');
            $path = $file->store(USERS_PROFILE_FULL_UPLOAD_SHORT_PATH);
            $name = $file->getClientOriginalName();
            $uploadedFileName = $file->hashName();
            $this->resizeImage($uploadedFileName, USERS_PROFILE_FULL_UPLOAD_PATH, USERS_PROFILE_SMALL_UPLOAD_PATH, 250, 250);
            
        }

        if($request->is_default_profile == 1){
            $record->is_default_profile = 1;
            UserProfile::where('user_id', $user->id)->where('id','<>',$request->id)->update(['is_default_profile' => 0]);
        } else{
            $record->is_default_profile = 0;
            UserProfile::where('user_id', $user->id)->where('id','<>',$request->id)->update(['is_default_profile' => 1]);
        }

            $prevrecord = UserProfile::where('id', $request->id)->first();
            if(!$request->image) $uploadedFileName = $prevrecord->profile_picture;
            UserProfile::where('id', $request->id)->update(['contact' => $record->contact, 'address' => $record->address, 'email' => $record->email, 'is_default_profile' => $record->is_default_profile, 'profile_picture' => $uploadedFileName]);

            User::where('id', $user->id)->update(['birth_date' => $request->birth_date,'gender' => $request->gender]);
        
        return Redirect::route('profile.personal_profile_edit')->with('status', 'personalprofile-updated');
    }

    /**
     * Display the user's professional profile form.
     */
    public function professional_profile_edit(Request $request): View
    {
        return view('profile.professional_profile_edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's professional profile edit form.
     */
    public function professional_profile_update(ProfileUpdateRequest $request): RedirectResponse
    {  
        define('USERS_PROFILE_FULL_UPLOAD_SHORT_PATH', 'public/images/full/');
        define('USERS_PROFILE_FULL_UPLOAD_PATH', storage_path() . '/app/public/images/full/');
        define('USERS_PROFILE_SMALL_UPLOAD_PATH',  storage_path() . '/app/public/images/small/');

        $user = Auth::user();
       
        $record = new UserProfile();
        $record->user_id = $user->id;
        $record->contact = $request->contact;
        $record->address = $request->address;
        $record->email = $request->email;
        $record->company_name = $request->company_name;
        $record->professional_role = $request->professional_role;
        $record->experience = $request->experience;  
        $profile_type = 2;
        

        if($request->image){
        
            $small_path = storage_path('images/small/');
            //$full_path = storage_path('images/full/');
            $file = $request->file('image');
            $path = $file->store(USERS_PROFILE_FULL_UPLOAD_SHORT_PATH);
            $name = $file->getClientOriginalName();
            $uploadedFileName = $file->hashName();
            $this->resizeImage($uploadedFileName, USERS_PROFILE_FULL_UPLOAD_PATH, USERS_PROFILE_SMALL_UPLOAD_PATH, 250, 250);
            
        }

        if($request->is_default_profile == 1){
        $record->is_default_profile = 1;
        UserProfile::where('user_id', $user->id)->where('id','<>',$request->id)->update(['is_default_profile' => 0]);
        } else{
            $record->is_default_profile = 0;
            UserProfile::where('user_id', $user->id)->where('id','<>',$request->id)->update(['is_default_profile' => 1]);
        }
        
        if($request->id){
            $prevrecord = UserProfile::where('id', $request->id)->first();
            if(!$request->image) $uploadedFileName = $prevrecord->profile_picture;
            UserProfile::where('id', $request->id)->update(['contact' => $record->contact, 'address' => $record->address, 'email' => $record->email, 'is_default_profile' => $record->is_default_profile, 'company_name' => $record->company_name, 'professional_role' => $record->professional_role, 'experience' => $record->experience, 'profile_picture' => $uploadedFileName,'profile_type' => $profile_type]);
        } else{
            $record->save();
        }

        return Redirect::route('profile.professional_profile_edit')->with('status', 'personalprofile-updated');
    }

    public function checkemail(Request $request)
    {
        $user = Auth::user();
        $user = UserProfile::where('email',$request->email)->where('user_id','<>',$user->id)->first();
        
        if($user){
            $data['message'] = true; 
        }
        else{
            $data['message'] = false; 
        }
        return Response::json($data);
        exit;
    }

    public function checkcontact(Request $request)
    {
        $user = Auth::user();
        $user = UserProfile::where('contact',$request->contact)->where('user_id','<>',$user->id)->first();
        
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
