<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Pastikan hanya user ter-autentikasi yang bisa membuat resep
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'ingredients' => ['required', 'string'],
            'instructions' => ['required', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'], // Pastikan setiap kategori ada di database
            'image' => ['nullable', 'image', 'max:2048'], // Opsional, gambar, maks 2MB
        ];
    }
}
