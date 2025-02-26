<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Modify based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'contact_number' => 'required|digits:10',
            'postcode' => 'required|digits:6',
            'gender' => 'required',
            'roles' => 'required|array',
            'city_id' => 'required|exists:cities,id',
            'state_id' => 'required|exists:states,id',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,docx|max:2048'
        ];
    }
}
