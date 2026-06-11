<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    /**
     * правила проверки, которые применяются к запросу
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

    /**
     * Доп. проверка: число гостей не должно превышать вместимость номера.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $room = Room::find($this->input('room_id'));

            if ($room && (int) $this->input('guests') > $room->capacity) {
                $validator->errors()->add(
                    'guests',
                    __('messages.guests_exceed_capacity', ['count' => $room->capacity])
                );
            }
        });
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

