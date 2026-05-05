<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

// Schema: users(id, name, email, password, bio, avatar)
// username, role, niche → schema-ல் இல்லை → remove
class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId   = $this->route('user')?->id;
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => [
                $isUpdate ? 'nullable' : 'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers(),
            ],
            'bio'      => ['nullable', 'string', 'max:1000'],
            // avatar → schema: varchar column stores path
            'avatar'   => [
                'nullable',
                File::image()->max(2 * 1024),   // 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.unique'   => 'This email is already registered.',
        ];
    }
}
