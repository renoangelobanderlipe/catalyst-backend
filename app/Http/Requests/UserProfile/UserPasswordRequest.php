<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserPasswordRequest extends FormRequest
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
      "current_password" => "required|string",
      "new_password" => [
        'required',
        Password::min(8)
          ->mixedCase()
          ->uncompromised(3)
      ],
    ];
  }
}
