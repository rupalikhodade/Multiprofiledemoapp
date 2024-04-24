<style>
    .error{
        color: red !important;
    }
    </style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

<script>
        $(document).ready(function() {
            $("#registerForm").validate({
                rules: {
                    first_name: {
                        required: true,
                        alphanumeric: true,
                        minlength: 3,
                        maxlength: 25,

                    },
                    last_name: {
                        required: true,
                        minlength: 3,
                        maxlength: 25
                    },
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 25,
                        remote: {
                            url: "{{ route('checkusername') }}",
                            type: "post",
                            data: {email: $("username").val(),"_token": "{{ csrf_token() }}"},
                            dataFilter: function (data) {
                                var json = JSON.parse(data);
                                if (json.message == true) {
                                    return "\"" + "Please use another username" + "\"";
                                } else {
                                    return 'true';
                                }
                            }
                        }
                    },
                    password: {
                        required: true,
                        minlength: 8,
                        maxlength: 15,
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        maxlength: 15,
                        equalTo: "#password"
                    },
                    contact: {
                        required: true,
                        maxlength: 15,
                        number: true,
                        minlength: 3,
                        remote: {
                            url: "{{ route('checkregcontact') }}",
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
                    email: {
                        required: true,
                        maxlength: 100,
                        minlength: 3,
                         remote: {
                            url: "{{ route('checkregemail') }}",
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
                },
                messages: {
	            "password_confirmation": {
	                equalTo: "Please enter same as the password",
	            }
	        }

            });
        });
    </script>
@extends('layouts.default')
@yield('content')
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- First Name -->
        <div>
            <x-input-label for="first_name" :value="__('First Name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- Last Name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('Last Name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autocomplete="last_name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>
        
        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Username -->
         <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Contact -->
        <div class="mt-4">
            <x-input-label for="contact" :value="__('Contact')" />
            <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required autocomplete="contact" />
            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
