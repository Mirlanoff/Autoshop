<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:2', 'max:255'],
            'phone'         => ['required', 'string', 'min:6', 'max:20'],
            'address'       => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'Введите имя',
            'customer_name.min'      => 'Имя должно содержать минимум 2 символа',
            'phone.required'         => 'Введите номер телефона',
            'phone.min'              => 'Номер телефона слишком короткий',
        ];
    }
}
