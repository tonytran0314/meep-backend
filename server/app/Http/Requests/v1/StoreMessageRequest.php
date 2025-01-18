<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreMessageRequest extends FormRequest
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
            'room_id' => ['required', 'exists:rooms,id'],
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::exists('room_user', 'user_id')->where('room_id', $this->room_id),
                function ($attribute, $value, $fail) {
                    if ((int)$value !== Auth::id()) {
                        $fail('Invalid user_id.');
                    }
                },
            ],
            'content' => ['required', 'string', 'max:65535'],
        ];
    }
}
