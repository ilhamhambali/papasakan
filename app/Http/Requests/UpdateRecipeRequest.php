<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Gunakan policy yang sudah kita buat
        return $this->user()->can('update', $this->recipe);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'ingredients' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
