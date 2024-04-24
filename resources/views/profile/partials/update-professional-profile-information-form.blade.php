<style>
    .error{
        color: red !important;
    }
    </style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script>
        $(document).ready(function() {
            $("#ProfessionalForm").validate({
                rules: {
                    contact: {
                        required: true,
                        maxlength: 15,
                        number: true,
                        minlength: 10,
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
                        required: true,
                        maxlength: 100,
                        minlength: 3
                    },
                    company_name: {
                        required: true,
                        maxlength: 50,
                        minlength: 3
                    },
                    professional_role: {
                        required: true,
                        maxlength: 50,
                        minlength: 3
                    },
                    experience: {
                        required: true,
                        maxlength: 50,
                        minlength: 3
                    },
                    email: {
                        required: true,
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
                    },
                    image: {
                        //extension: "jpeg|png|jpg|gif|svg"
                    }
                },
                messages: {
	            "image": {
	                //extension: "File type not supported ",
	            }
            }
        });
    });
    </script>
@php
$loginuser = Auth::user();
use App\Models\UserProfile;
$user= UserProfile::where('user_id', $loginuser->id)->where('profile_type','2')->first();
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
            {{ __('Professional Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your professional profile information.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.professional_profile_update') }}" class="mt-6 space-y-6" enctype="multipart/form-data" id="ProfessionalForm">
        @csrf
        @method('patch')

        {{ $email = $contact = $address = $experience = $company_name = $professional_role = $is_default_profiles = $profile_picture = ''; }}
        @if(!empty($user))
        <?php 
        if(isset($user->email) ? $email = $user->email : '') 
        if(isset($user->contact) ? $contact = $user->contact : '') 
        if(isset($user->address) ? $address = $user->address : '')
        if(isset($user->company_name) ? $company_name = $user->company_name : '')
        if(isset($user->professional_role) ? $professional_role = $user->professional_role : '')
        if(isset($user->experience) ? $experience = $user->experience : '') 
        if($user->is_default_profile == 1) { $is_default_profiles = 1;} else { $is_default_profiles = '';}
        if(isset($user->profile_picture) ? $profile_picture = $user->profile_picture : '') 
        
        ?>
        <x-text-input id="id" name="id" type="hidden" class="mt-1 block w-full" :value="old('id', $user->id)" autocomplete="username" />
        @endif
        
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $email)" autofocus autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="contact" :value="__('Contact')" />
            <x-text-input id="contact" name="contact" type="text" class="mt-1 block w-full" :value="old('contact', $contact)" autofocus autocomplete="contact" />
            <x-input-error class="mt-2" :messages="$errors->get('contact')" />
        </div>
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $address)" autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="company_name" :value="__('Company Name')" />
            <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="old('company_name', $company_name)" autofocus autocomplete="company_name" />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>
        <div>
            <x-input-label for="professional_role" :value="__('Professional Role')" />
            <x-text-input id="professional_role" name="professional_role" type="text" class="mt-1 block w-full" :value="old('professional_role', $professional_role)" autofocus autocomplete="professional_role" />
            <x-input-error class="mt-2" :messages="$errors->get('professional_role')" />
        </div>
        <div>
            <x-input-label for="experience" :value="__('Experience')" />
            <x-text-input id="experience" name="experience" type="text" class="mt-1 block w-full" :value="old('experience', $experience)" autofocus autocomplete="experience" />
            <x-input-error class="mt-2" :messages="$errors->get('experience')" />
        </div>

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <input type="file" class="form-control" name="image" id="image"></div>        
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
