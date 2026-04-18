<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Faq extends Model
{
    use HasFactory;

    // Mengizinkan Controller untuk mengisi kolom ini secara massal
    protected $fillable = [
        'question',
        'answer',
    ];

    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question'); // Untuk input satu baris
            $table->text('answer');     // Menggunakan text karena jawaban bisa panjang
            $table->timestamps();
        });
    }
}
