<style>
    .error{
        color: red !important;
    }
    </style>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" ></script>

<script>

$(function () {
    var fullDate = new Date();
    var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : (fullDate.getMonth()+1);
    var currentDate = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + (fullDate.getDate() -1);
    $('#birth_date').attr('max' , currentDate);
});

        $(document).ready(function() {    

            $("#personalForm").validate({
                rules: {
                    contact: {
                        maxlength: 15,
                        number: true,
                        minlength: 3,
                        remote: {
                            url: "{{ route('checkcontact') }}",
                            type: "post",
                            data: {email: $("contact").val(),"_token": "{{ csrf_token() }}"},
                            dataFilter: function (data) {
                                var json = JSON.parse(data);
                                if (json.message == true) {
                                    return "\"" + "Please use another Contact" + "\"";
                                } else {
                                    return 'true';
                                }
                            }
                        }
                    },
                    address: {
                      
                        maxlength: 100,
                        minlength: 3
                    },
                    email: {
                      
                        maxlength: 100,
                        minlength: 3,
                        remote: {
                            url: "{{ route('checkemail') }}",
                            type: "post",
                            data: {email: $("email").val(),"_token": "{{ csrf_token() }}"},
                            dataFilter: function (data) {
                                var json = JSON.parse(data);
                                if (json.message == true) {
                                    return "\"" + "Please use another email" + "\"";
                                } else {
                                    return 'true';
                                }
                            }
                        }
                    }
                }
            });
        });


    </script>
@php
$loginuser = Auth::user(); 
use App\Models\UserProfile;
$user= UserProfile::where('user_id', $loginuser->id)->where('profile_type','1')->first();
@endphp

<section>
    @if (session('status') === 'personalprofile-updated')
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="text-sm text-gray-600" style="background-color:darkseagreen"
        ><b>{{ __('Saved Successfully.') }}</b></p>
    @endif
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Personal Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your personal profile information.") }}
        </p>
    </header>
 
    <form id="personal-profile-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.personal_profile_update') }}" class="mt-6 space-y-6" id="personalForm" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{ $email = $contact = $address = $is_default_profiles = $profile_picture = ''; }}
        @if(!empty($user))
        <?php 
        if(isset($user->email) ? $email = $user->email : '') 
        if(isset($user->contact) ? $contact = $user->contact : '') 
        if(isset($user->address) ? $address = $user->address : '') 
        if($user->is_default_profile == 1) {  $is_default_profiles = 1;} else { $is_default_profiles = '';}
        if(isset($user->profile_picture) ? $profile_picture = $user->profile_picture : '') 
        if($user->is_default_profile === 1){$is_default_profiles = 1;}else{$is_default_profiles = 0;}
        ?>
        <x-text-input id="id" name="id" type="hidden" class="mt-1 block w-full" :value="old('id', $user->id)" autocomplete="username" />
        @endif

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $email)" autofocus autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="contact" :value="__('Contact')" />
            <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" :value="old('contact', $contact)" number autofocus autocomplete="contact" />
            <x-input-error class="mt-2" :messages="$errors->get('contact')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $address)" autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="birth_date" :value="__('Birth Date')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $loginuser->birth_date)" autofocus autocomplete="birth_date"/>
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <select class="mt-1 block w-full" id="gender" name="gender" focus value="$loginuser->gender">
                <option value="" disabled selected>Select gender</option>        
                <option @if($loginuser->gender == 1) selected @endif value="1">Male</option>                                             
                <option @if($loginuser->gender == 2) selected @endif value="2">Female</option>        
                <option @if($loginuser->gender == 3) selected @endif value="3">Transgender</option>                                                     
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('gender')" />
        </div>

        <div>
            <x-input-label for="image" :value="__('Profile Picture')" />
            <input type="file" class="mt-1 block w-full" name="image" id="image"></div>        
            <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            @if($profile_picture)
            <img src="{{ asset('/storage/images/full/'.$user->profile_picture) }}" style="height: 50px;width:100px;">
            @endif
        </div>

        <div>
            <x-input-label for="is_default_profile" :value="__('Is this default profile')" />
            <input id="is_default_profile" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_default_profile" value="1" {{  ($is_default_profiles == 1 ? ' checked' : '') }}>
            <x-input-error class="mt-2" :messages="$errors->get('is_default_profile')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

        </div>
    </form>
</section>
