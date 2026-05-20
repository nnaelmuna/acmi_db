@props([
    'id' => 'status',
    'name' => 'status',
])

<div>
    <label for="{{ $id }}" class="mb-2 block text-xs font-semibold text-gray-600">Status</label>

    <select id="{{ $id }}" name="{{ $name }}"
        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20">
        <option value="published">Published</option>
        <option value="draft">Draft</option>
        <option value="archived">Archived</option>
    </select>
</div>
