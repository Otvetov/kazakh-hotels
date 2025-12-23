<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
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
            'check_in' => ['required', 'date', 'after_or_equal:' . date('Y-m-d')],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Необходимо выбрать номер.',
            'room_id.exists' => 'Выбранный номер не существует.',
            'check_in.required' => 'Необходимо указать дату заезда.',
            'check_in.date' => 'Дата заезда должна быть корректной датой.',
            'check_in.after_or_equal' => 'Дата заезда не может быть в прошлом.',
            'check_out.required' => 'Необходимо указать дату выезда.',
            'check_out.date' => 'Дата выезда должна быть корректной датой.',
            'check_out.after' => 'Дата выезда должна быть позже даты заезда.',
            'guests.required' => 'Необходимо указать количество гостей.',
            'guests.integer' => 'Количество гостей должно быть числом.',
            'guests.min' => 'Количество гостей должно быть не менее 1.',
        ];
    }
}

