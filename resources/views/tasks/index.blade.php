<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Management') }}
            </h2>
            <a href="{{ route('tasks.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Task
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Search and Filter Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Search & Filter Tasks</h3>
                    
                    <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div class="row gy-4">
                                <div class="col-xl-4">
                                    <!-- Search -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Search</label>
                                        <input type="text" name="search" value="{{ $filters['search'] }}" 
                                               placeholder="Search tasks..."
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <!-- Status Filter -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">All Statuses</option>
                                            <option value="pending" {{ $filters['status'] == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ $filters['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $filters['status'] == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                     <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <select name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $filters['category_id'] == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                </div>
                                <div class="col-xl-4">
                                    <!-- Priority Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Priority</label>
                                <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Priorities</option>
                                    <option value="low" {{ $filters['priority'] == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $filters['priority'] == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $filters['priority'] == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ $filters['priority'] == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                            </div>
                                </div>
                                <div class="col-xl-4">
                                    <!-- Sort By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Sort By</label>
                                <select name="sort_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="priority_score" {{ $filters['sort_by'] == 'priority_score' ? 'selected' : '' }}>Priority Score</option>
                                    <option value="due_date" {{ $filters['sort_by'] == 'due_date' ? 'selected' : '' }}>Due Date</option>
                                    <option value="created_at" {{ $filters['sort_by'] == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                    <option value="title" {{ $filters['sort_by'] == 'title' ? 'selected' : '' }}>Title</option>
                                </select>
                            </div>
                                </div>
                                <div class="col-xl-4">
                                    <!-- Sort Direction -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Order</label>
                                <select name="sort_direction" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="desc" {{ $filters['sort_direction'] == 'desc' ? 'selected' : '' }}>Descending</option>
                                    <option value="asc" {{ $filters['sort_direction'] == 'asc' ? 'selected' : '' }}>Ascending</option>
                                </select>
                            </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 w-100">
                            <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Apply Filters
                            </button>
                            <a href="{{ route('tasks.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Actions Form -->
            <form id="bulk-form" method="POST" action="{{ route('tasks.bulk-update') }}">
                @csrf
                
                <!-- Bulk Actions Bar -->
                <div id="bulk-actions" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between">
                        <span id="selected-count" class="text-sm text-blue-800"></span>
                        <div class="space-x-2">
                            <button type="button" onclick="performBulkAction('complete')" 
                                    class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                Mark Complete
                            </button>
                            <button type="button" onclick="performBulkAction('incomplete')" 
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                                Mark Incomplete
                            </button>
                            <button type="button" onclick="performBulkAction('delete')" 
                                    class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                    onclick="return confirm('Are you sure you want to delete selected tasks?')">
                                Delete Selected
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tasks Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border w-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left border">
                                            <input type="checkbox" id="select-all" class="rounded border-gray-300">
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Task</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Priority</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Category</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Due Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Assigned To</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($tasks as $task)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 border">
                                                <input type="checkbox" name="task_ids[]" value="{{ $task->id }}" 
                                                       class="task-checkbox rounded border-gray-300">
                                            </td>
                                            <td class="px-6 py-4 border">
                                                <div>
                                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                        {{ $task->title }}
                                                    </a>
                                                    @if($task->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @switch($task->status)
                                                        @case('completed') bg-green-100 text-green-800 @break
                                                        @case('in_progress') bg-yellow-100 text-yellow-800 @break
                                                        @default bg-red-100 text-red-800
                                                    @endswitch">
                                                    {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @switch($task->priority)
                                                        @case('urgent') bg-red-100 text-red-800 @break
                                                        @case('high') bg-orange-100 text-orange-800 @break
                                                        @case('medium') bg-yellow-100 text-yellow-800 @break
                                                        @default bg-green-100 text-green-800
                                                    @endswitch">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 border">
                                                <div class="flex items-center">
                                                    <span class="text-lg font-bold 
                                                        @if($task->priority_score >= 8) text-red-600
                                                        @elseif($task->priority_score >= 6) text-orange-600  
                                                        @elseif($task->priority_score >= 4) text-yellow-600
                                                        @else text-green-600 @endif">
                                                        {{ number_format($task->priority_score, 1) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 border text-sm text-gray-900">
                                                {{ $task->category->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 border text-sm text-gray-900">
                                                @if($task->due_date)
                                                    <span class="@if($task->due_date < now() && $task->status !== 'completed') text-red-600 font-medium @endif">
                                                        {{ $task->due_date->format('M j, Y') }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">No due date</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border text-sm text-gray-900">
                                                {{ $task->assignedTo->name ?? 'Unassigned' }}
                                            </td>
                                            <td class="px-6 py-4 border text-sm font-medium space-x-2">
                                                <div class="d-flex align-items-center gap-3">
                                                    <a href="{{ route('tasks.show', $task) }}" class="text-blue-600 hover:text-blue-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                                        </svg>
                                                    </a>
                                                    <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                                        </svg>
                                                    </a>
                                                    <button type="button" onclick="toggleTaskStatus({{ $task->id }})" 
                                                            class="text-green-600 hover:text-green-900">
                                                        {{ $task->status === 'completed' ? 'Reopen' : 'Complete' }}
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                                No tasks found. <a href="{{ route('tasks.create') }}" class="text-blue-600 hover:text-blue-900">Create your first task!</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($tasks->hasPages())
                            <div class="mt-6">
                                {{ $tasks->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for bulk actions and AJAX -->
    <script>
        // Bulk selection logic
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.task-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        document.querySelectorAll('.task-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
            const bulkActionsDiv = document.getElementById('bulk-actions');
            const selectedCount = document.getElementById('selected-count');

            if (checkedBoxes.length > 0) {
                bulkActionsDiv.classList.remove('hidden');
                selectedCount.textContent = `${checkedBoxes.length} task(s) selected`;
            } else {
                bulkActionsDiv.classList.add('hidden');
            }
        }

        function performBulkAction(action) {
            const form = document.getElementById('bulk-form');
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            if (action === 'delete' && !confirm('Are you sure you want to delete the selected tasks?')) {
                return;
            }

            form.submit();
        }

        // AJAX status toggle
        async function toggleTaskStatus(taskId) {
            try {
                const response = await fetch(`/tasks/${taskId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    location.reload(); // Simple reload for now
                } else {
                    alert('Error updating task status');
                }
            } catch (error) {
                alert('Error updating task status');
            }
        }
    </script>
</x-app-layout>