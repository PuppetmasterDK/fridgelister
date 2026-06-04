<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $fridge = $this->route('fridge');

        return $fridge->user_id === $this->user()->id || $fridge->isSharedWith($this->user());
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'quantity' => ['nullable', 'numeric', 'min:0.01', 'max:9999'],
            'unit' => ['nullable', 'string', 'max:20'],
            'best_before' => ['nullable', 'date'],
        ];
    }
}
