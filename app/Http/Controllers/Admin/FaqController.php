<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Menampilkan halaman FAQ (CMS Screen)
    public function index()
    {
        $faqs = Faq::latest()->get(); // Mengambil data terbaru dari atas
        return view('faq', compact('faqs')); 
        // Pastikan nama view kamu sesuai, misalnya 'faq.blade.php'
    }

    // Memproses data dari Pop Out Add FaQ
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        Faq::create($request->all());

        return redirect()->route('faq')->with('success', 'FAQ berhasil ditambahkan!');
    }

    // Memproses data dari Pop Out Edit FaQ
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = Faq::findOrFail($id);
        $faq->update($request->all());

        return redirect()->route('faq')->with('success', 'FAQ berhasil diperbarui!');
    }

    // Menghapus data FAQ (Biasanya dibutuhkan di CMS)
    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq')->with('success', 'FAQ berhasil dihapus!');
    }
}
