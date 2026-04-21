<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'string', 'min:6', 'max:20'],
        ];
    }
}
