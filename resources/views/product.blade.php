@extends('layouts.app') 

@section('title', 'Product - ACMI')

@section('page_title', 'Product')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
        <img src="{{ asset('assets/images/engine.jpg') }}" class="rounded-lg w-full h-48 object-cover">
        <h3 class="mt-4 font-bold text-lg">Green Energy : Solutions</h3>
        <p class="text-gray-500 text-sm">PT Energi Hijau Indonesia</p>
        <button class="w-full mt-4 bg-acmi-darkblue text-white py-2 rounded-lg">Detail</button>
    </div>
    {{-- Ulangi atau pakai @foreach nanti kalau sudah pakai database --}}
</div>
@endsection