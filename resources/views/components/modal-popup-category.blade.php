@props([
    'tabs' => [],
    'categories' => [],
])

{{-- Modal pop-up Category --}}
<div id="categoryModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/50 backdrop-blur-sm transition-opacity duration-300">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl scale-95 transform transition-transform duration-300">
        
        {{-- Header Modal --}}
        <div class="mb-5 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-800">Manage Categories</h3>
            <button type="button" onclick="closeCategoryModal()" class="text-gray-400">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        {{-- Daftar Kategori yang Ada --}}
        <div class="mb-5">
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Existing Categories</p>
            <div id="categoryList" class="max-h-52 overflow-y-auto space-y-2 pr-1">
                @foreach($categories as $category)
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5" id="category-item-{{ $category->id }}">
                        
                        {{-- Normal State --}}
                        <div class="flex items-center justify-between normal-state-{{ $category->id }}">
                            <span class="text-sm text-gray-700">{{ $category->name }}</span>
                            <button
                                type="button"
                                onclick="askDeleteCategory({{ $category->id }})"
                                class="ml-3 flex-shrink-0 text-gray-400 hover:text-red-500 transition"
                                title="Delete category"
                            >
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
        
                        {{-- Confirm State (hidden by default) --}}
                        <div class="hidden items-center justify-between gap-3 confirm-state-{{ $category->id }}">
                            <span class="text-sm font-medium text-red-500 whitespace-nowrap">Delete "{{ $category->name }}"?</span>
                            <div class="flex gap-2 flex-shrink-0">
                                <button
                                    type="button"
                                    onclick="cancelDeleteCategory({{ $category->id }})"
                                    class="rounded-lg border border-gray-300 px-3 py-1 text-xs font-medium text-gray-600 hover:bg-gray-100 transition"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    onclick="confirmDeleteCategory({{ $category->id }})"
                                    class="rounded-lg bg-red-500 px-3 py-1 text-xs font-semibold text-white hover:bg-red-600 transition"
                                >
                                    Yes, Delete
                                </button>
                            </div>
                        </div>
        
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Divider --}}
        <div class="mb-5 border-t border-gray-200"></div>

        {{-- Form Tambah Kategori Baru --}}
        <div>
            <p class="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-500">Add New Category</p>
            <form action="{{ route('categories.store') }}" method="POST" id="formAddCategory">
                @csrf
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="name" 
                        id="newCategoryInput"
                        placeholder="e.g. Technology" 
                        class="flex-1 rounded-xl border border-gray-300 px-4 py-2.5 text-sm focus:border-acmi-blueprimer focus:outline-none focus:ring-2 focus:ring-acmi-blueprimer/20" 
                        required
                    >
                    <button 
                        type="submit" 
                        class="rounded-xl bg-acmi-blueprimer px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-acmi-darkblue whitespace-nowrap"
                    >
                        + Add
                    </button>
                </div>
            </form>
        </div>

        {{-- Tombol Close --}}
        <div class="mt-5 flex justify-end">
            <button 
                type="button" 
                onclick="closeCategoryModal()" 
                class="rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Done
            </button>
        </div>

    </div>
</div>