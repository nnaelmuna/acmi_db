<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MemberRequest extends FormRequest
{
   public function authorize(): bool
{
    return true; // <--- PASTIKAN SUDAH DIGANTI JADI TRUE
}

public function rules(): array
{
    return [
        // Biarkan kosong dulu sementara tidak apa-apa, yang penting filenya ada
    ];
}
}
