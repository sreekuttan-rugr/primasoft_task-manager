<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Task
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

            <form action="{{ route('tasks.update-fake', $task->id) }}" method="POST">
                @csrf

                    <!-- Title -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Title</label>
                        <input type="text" name="title" value="{{ old('title', $task->title) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('title') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $task->description) }}</textarea>
                        @error('description') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Category</label>
                        <select name="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $task->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Urgency, Impact, Effort -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                        @foreach(['urgency', 'impact', 'effort'] as $field)
                            <div>
                                <label class="block text-gray-700">{{ ucfirst($field) }} (1‑10)</label>
                                <input type="number" name="{{ $field }}" min="1" max="10"
                                       value="{{ old($field, $task->$field) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @error($field) <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        @endforeach
                    </div>

                    <!-- Due Date -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Due Date</label>
                        <input type="date" name="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('due_date') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Assigned To -->
                    <div class="mb-6">
                        <label class="block text-gray-700">Assigned To</label>
                        <input type="text" name="assigned_to" value="{{ old('assigned_to', $task->assigned_to) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        @error('assigned_to') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('tasks.show', $task) }}"
                           class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white rounded-md">
                           Cancel
                        </a>
                        <button type="submit">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
