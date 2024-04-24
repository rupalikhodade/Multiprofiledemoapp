<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['string', 'max:50'],
            'last_name' => ['string', 'max:50'],
            'gender' => ['Int', 'max:2'],
            'birth_date' => ['string', 'max:255'],
            //'email' => ['string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'email' => ['string', 'lowercase', 'max:100'],
            'contact' => ['string', 'max:15'],
            'company_name' => ['string', 'max:50'],
            'professional_role' => ['string', 'max:50'],
            'experience' => ['string', 'max:50'],
            'is_default_profile' => ['Int', 'max:2'],
            'image' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:20000'],
        ];
    }
}
