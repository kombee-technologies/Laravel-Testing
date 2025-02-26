<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Change if authorization is required
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users',
            'contact_number' => 'required|digits:10',
            'postcode' => 'required|digits:6',
            'password' => 'required|confirmed',
            'gender' => 'required',
            'roles' => 'required|array',
            'hobbies' => 'nullable|array',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
        ];
    }
}
