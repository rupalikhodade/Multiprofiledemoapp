@php
$loginuser = Auth::user();
use App\Models\UserProfile;
$user= UserProfile::where('user_id', $loginuser->id)->where('is_default_profile','1')->first();
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($user->profile_type == 1)
                   <b> {{ __("Your Personal profile details are below") }} </b>
                   @else
                   <b> {{ __("Your Professional profile details are below") }} </b>
                   @endif
                </div>
                <div class="p-6 text-gray-900">   
                    <p><b>{{'First Name'}} :</b> {{ $loginuser->first_name ? $loginuser->first_name : 'N/A'}}</p> 
                    <p><b>{{'Last Name'}} :</b> {{ $loginuser->last_name ? $loginuser->last_name : 'N/A'}}</p> 
                    @if($user->profile_type == 1)
                    <p><b>{{'Birth Date'}} : </b> {{ $loginuser->birth_date ? date("d-M-Y", strtotime($loginuser->birth_date))  : 'N/A'}}</p>            
                    <p><b>{{'Gender'}} : </b> 
                    @if($loginuser->gender == 1)  {{'Male'}}
                    @elseif($loginuser->gender == 2) {{'Female'}}
                    @elseif($loginuser->gender == 3) {{'Transgender'}}                    
                    @elseif($loginuser->gender == null) {{'N/A'}}
                    @elseif($loginuser->gender == 0) {{'Other'}}
                    @else {{'N/A'}}
                    @endif</p>
                    @endif
                    <p><b>{{'Email'}} :</b> {{ $user->email ? $user->email : 'N/A'}}</p>            
                    <p><b>{{'Contact'}} : </b> {{ $user->contact ? $user->contact : 'N/A'}}</p>
                    <p><b>{{'Address'}} : </b> {{ $user->address ? $user->address : 'N/A'}}</p>
                    
                    @if($user->profile_type == 2)
                    <p><b>{{'Company Name'}} : </b> {{ $user->company_name ? $user->company_name : 'N/A'}}</p>
                    <p><b>{{'Professional Role'}} : </b> {{ $user->professional_role ? $user->professional_role : 'N/A'}}</p>
                    <p><b>{{'Experience'}} : </b> {{ $user->experience ? $user->experience : 'N/A'}}</p>
                   @endif

                    @if($user->profile_picture)
                        <p><b>Profile Image : <img src="{{ asset('/storage/images/full/'.$user->profile_picture) }}" style="height: 50px;width:100px;"></b></p>
                    @else 
                        <span>No profile image found!</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
