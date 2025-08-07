<?php

namespace App\Http\Requests;

use App\Models\Article; // [BARU] Tambahkan ini
use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // [FIX] Gunakan policy untuk memeriksa apakah user boleh membuat artikel.
        // Ini lebih aman dan konsisten dengan UpdateArticleRequest.
        return $this->user()->can('create', Article::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'], // Opsional, gambar, maks 2MB
        ];
    }
}
