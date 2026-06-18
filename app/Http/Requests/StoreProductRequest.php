<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => 'required|string|max:255',
            'title_id' => 'required|string|max:255',

            'description_en' => 'required|string',
            'description_id' => 'required|string',

            'features_en' => 'required|string',
            'features_id' => 'required|string',

            'category' => 'nullable|string|max:255',
            'website' => 'required|url|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            'status' => 'required|in:draft,published',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $hasEnglish =
                request()->filled('title_en') ||
                request()->filled('description_en') ||
                request()->filled('features_en');

            $hasIndonesian =
                request()->filled('title_id') ||
                request()->filled('description_id') ||
                request()->filled('features_id');

            if (!$hasEnglish && !$hasIndonesian) {
                $validator->errors()->add(
                    'content',
                    'Please fill in either English or Indonesian content.'
                );
            }

            if ($hasEnglish && $hasIndonesian) {
                $validator->errors()->add(
                    'content',
                    'Please choose only one language: English or Indonesian.'
                );
            }

            if ($hasEnglish) {
                if (
                    !request()->filled('title_en') ||
                    !request()->filled('description_en') ||
                    !request()->filled('features_en')
                ) {
                    $validator->errors()->add(
                        'content_en',
                        'Please complete all English content fields.'
                    );
                }
            }

            if ($hasIndonesian) {
                if (
                    !request()->filled('title_id') ||
                    !request()->filled('description_id') ||
                    !request()->filled('features_id')
                ) {
                    $validator->errors()->add(
                        'content_id',
                        'Please complete all Indonesian content fields.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'image.max' => 'Image size maximum is 2MB.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}