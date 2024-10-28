<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "first_name" => "required|min:2|max:25",
            "last_name" => "required|min:2|max:25",
            "email" => "required|email|unique:users,email",
            "user_name" => "required|alpha_num:ascii|min:4|max:50|unique:users,user_name",
            "password" => "required|min:6|max:50|confirmed"
        ];
    }
}
