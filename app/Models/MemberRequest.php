<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan melakukan request ini (biasanya true jika sudah login admin).
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Aturan validasi untuk data Member.
     */
    public function rules(): array
    {
        // Ambil ID member jika ini adalah proses update (untuk mengecualikan unique email)
        $memberId = $this->route('member') ? $this->route('member')->id : null;

        return [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:members,email,' . $memberId,
            'phone'        => 'required|string|max:30',
            'company_name' => 'nullable|string|max:255',
            'industry'     => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',
            'company_url'  => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'status'       => 'nullable|in:active,inactive,suspended',
        ];
    }
}