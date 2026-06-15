<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
            'hotel_id' => ['required', 'exists:hotels,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => 'Не указан отель.',
            'hotel_id.exists' => 'Выбранный отель не существует.',
            'rating.required' => 'Пожалуйста, поставьте оценку.',
            'rating.integer' => 'Оценка должна быть числом.',
            'rating.min' => 'Оценка должна быть от 1 до 5.',
            'rating.max' => 'Оценка должна быть от 1 до 5.',
            'comment.required' => 'Пожалуйста, напишите комментарий.',
            'comment.min' => 'Комментарий должен содержать не менее :min символов.',
            'comment.max' => 'Комментарий не должен превышать :max символов.',
        ];
    }
}


