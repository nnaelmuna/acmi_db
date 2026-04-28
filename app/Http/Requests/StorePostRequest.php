<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Sudah dihandle middleware auth
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'content'     => 'nullable|string',
            'categories'  => 'nullable|array',
            'image'       => 'nullable|image|max:2048',
            'status'      => 'nullable|in:draft,published',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul post wajib diisi.',
            'image.max'      => 'Ukuran gambar maksimal 2MB.',
            'status.in'      => 'Status tidak valid.',
        ];
    }
}