<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Task') }}
            </h2>
            <a href="{{ route('tasks.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
                        @csrf

                        <!-- Task Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Task Title *</label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Task Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-300 @enderror">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority *</label>
                                <select name="priority" id="priority" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('priority') border-red-300 @enderror">
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Category -->
                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                                <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category_id') border-red-300 @enderror">
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('due_date') border-red-300 @enderror">
                                @error('due_date')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Assigned To (Optional for now) -->
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
                            <select name="assigned_to" id="assigned_to"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('assigned_to') border-red-300 @enderror">
                                <option value="">Assign to myself</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Priority Scoring Section -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Priority Scoring</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Help us calculate the priority score by rating these factors (1-10 scale):
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Urgency -->
                                <div>
                                    <label for="urgency" class="block text-sm font-medium text-gray-700">
                                        Urgency * 
                                        <span class="text-xs text-gray-500">(How time-sensitive?)</span>
                                    </label>
                                    <input type="range" name="urgency" id="urgency" min="1" max="10" 
                                           value="{{ old('urgency', 5) }}" 
                                           class="mt-1 block w-full @error('urgency') border-red-300 @enderror"
                                           oninput="updateScorePreview()">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>Low (1)</span>
                                        <span id="urgency-value" class="font-medium">5</span>
                                        <span>High (10)</span>
                                    </div>
                                    @error('urgency')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Impact -->
                                <div>
                                    <label for="impact" class="block text-sm font-medium text-gray-700">
                                        Impact * 
                                        <span class="text-xs text-gray-500">(How important?)</span>
                                    </label>
                                    <input type="range" name="impact" id="impact" min="1" max="10" 
                                           value="{{ old('impact', 5) }}" 
                                           class="mt-1 block w-full @error('impact') border-red-300 @enderror"
                                           oninput="updateScorePreview()">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>Low (1)</span>
                                        <span id="impact-value" class="font-medium">5</span>
                                        <span>High (10)</span>
                                    </div>
                                    @error('impact')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Effort -->
                                <div>
                                    <label for="effort" class="block text-sm font-medium text-gray-700">
                                        Effort * 
                                        <span class="text-xs text-gray-500">(How much work?)</span>
                                    </label>
                                    <input type="range" name="effort" id="effort" min="1" max="10" 
                                           value="{{ old('effort', 5) }}" 
                                           class="mt-1 block w-full @error('effort') border-red-300 @enderror"
                                           oninput="updateScorePreview()">
                                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                                        <span>Low (1)</span>
                                        <span id="effort-value" class="font-medium">5</span>
                                        <span>High (10)</span>
                                    </div>
                                    @error('effort')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Priority Score Preview -->
                            <div class="mt-4 p-3 bg-white rounded border">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">Calculated Priority Score:</span>
                                    <span id="priority-score-preview" class="text-lg font-bold text-blue-600">5.0</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Formula: (Urgency × Impact) ÷ Effort
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('tasks.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded">
                                Create Task
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for score preview -->
    <script>
        function updateScorePreview() {
            const urgency = parseFloat(document.getElementById('urgency').value);
            const impact = parseFloat(document.getElementById('impact').value);
            const effort = parseFloat(document.getElementById('effort').value);

            // Update individual value displays
            document.getElementById('urgency-value').textContent = urgency;
            document.getElementById('impact-value').textContent = impact;
            document.getElementById('effort-value').textContent = effort;

            // Calculate priority score using the same formula as the backend
            const priorityScore = (urgency * impact) / effort;
            document.getElementById('priority-score-preview').textContent = priorityScore.toFixed(1);

            // Color code the score
            const scoreElement = document.getElementById('priority-score-preview');
            if (priorityScore >= 8) {
                scoreElement.className = 'text-lg font-bold text-red-600';
            } else if (priorityScore >= 6) {
                scoreElement.className = 'text-lg font-bold text-orange-600';
            } else if (priorityScore >= 4) {
                scoreElement.className = 'text-lg font-bold text-yellow-600';
            } else {
                scoreElement.className = 'text-lg font-bold text-green-600';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', updateScorePreview);

        // Set minimum date to today for due_date
        document.getElementById('due_date').min = new Date().toISOString().split('T')[0];
    </script>
</x-app-layout>