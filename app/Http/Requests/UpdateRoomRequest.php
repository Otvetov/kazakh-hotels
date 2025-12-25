<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => ['sometimes', 'required', 'exists:hotels,id'],
            'name' => ['sometimes', 'required', 'string', 'max:100'],
            'price_per_night' => ['sometimes', 'required', 'numeric', 'min:0'],
            'capacity' => ['sometimes', 'required', 'integer', 'min:1'],
            'is_available' => ['sometimes', 'boolean'],
        ];
    }
}


