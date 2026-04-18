@extends('layouts.app')

@section('title', 'Dashboard CMS - ACMI')
@section('page_title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-3 gap-8">
        
        <div class="bg-white rounded-10px border-1px border-#1120B0 shadow-sm overflow-hidden">
            <div class="p-5 flex justify-between items-center">
                <p class="text-gray-600 text-lg font-medium">Total Member</p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                </svg>
            </div>
            <div class="border-t-1px border-[#1120B0]"></div>
            <div class="p-6">
                <h2 class="text-5xl font-bold text-black">{{ $totalMember ?? 0 }}</h2>
            </div>
        </div>
    
        <div class="bg-white rounded-[10px] border-1px border-#1120B0 shadow-sm overflow-hidden">
            <div class="p-5 flex justify-between items-center">
                <p class="text-gray-600 text-lg font-medium">New Member</p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                </svg>
            </div>
            <div class="border-t-1px border-[#1120B0]"></div>
            <div class="p-6">
                <h2 class="text-5xl font-bold text-black">{{ $newMember ?? 0 }}</h2>
            </div>
        </div>
    
        <div class="bg-white rounded-[10px] border-1px border-[#1120B0] shadow-sm overflow-hidden">
            <div class="p-5 flex justify-between items-center">
                <p class="text-gray-600 text-lg font-medium">Most View</p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-black">
                    <path d="M18.375 2.25c.621 0 1.125.504 1.125 1.125v17.25c0 .621-.504 1.125-1.125 1.125H15.75c-.621 0-1.125-.504-1.125-1.125V3.375c0-.621.504-1.125 1.125-1.125h2.625ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.625c.621 0 1.125.504 1.125 1.125v10.875c0 .621-.504 1.125-1.125 1.125h-2.625a1.125 1.125 0 0 1-1.125-1.125V8.625ZM3.75 13.125c0-.621.504-1.125 1.125-1.125h2.625c.621 0 1.125.504 1.125 1.125v6.375c0 .621-.504 1.125-1.125 1.125H4.875a1.125 1.125 0 0 1-1.125-1.125v-6.375Z" />
                </svg>
            </div>
            <div class="border-t-1px border-[#1120B0]"></div>
            <div class="p-6">
                <h2 class="text-5xl font-bold text-black">{{ $mostView ?? 0 }}</h2>
            </div>
        </div>

    </div>
@endsection