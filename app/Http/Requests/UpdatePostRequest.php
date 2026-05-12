<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title_en' => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'content_en' => 'nullable|string',

            'title_id' => 'nullable|string|max:255',
            'description_id' => 'nullable|string',
            'content_id' => 'nullable|string',

            'categories' => 'nullable|array',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => 'nullable|in:draft,published,archived',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $hasEnglish =
                request()->filled('title_en') ||
                request()->filled('description_en') ||
                request()->filled('content_en');

            $hasIndonesian =
                request()->filled('title_id') ||
                request()->filled('description_id') ||
                request()->filled('content_id');

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
                    !request()->filled('content_en')
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
                    !request()->filled('content_id')
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
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'Image format must be JPG, JPEG, PNG, or WEBP.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
