@extends('layouts.app')

@section('title', 'Subscription Screen - ACMI')
@section('page_title', 'Subscription')

@section('content')
    <div class="flex min-h-[70vh] items-center justify-center">
        <div class="text-center">

            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-3xl bg-acmi-softblue shadow-sm">
                <i class="fas fa-gear text-3xl text-acmi-blueprimer"></i>
            </div>

            <h1 class="text-4xl font-bold text-gray-900">
                Coming Soon
            </h1>

            <p class="mt-3 text-sm text-gray-400">
                This feature is currently under development.
            </p>

            <p class="mt-1 text-sm text-gray-400">
                Please stay tuned for upcoming updates.
            </p>

        </div>
    </div>
@endsection
