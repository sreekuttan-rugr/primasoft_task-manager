<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task Details
            </h2>
            <div class="space-x-2">
                @can('update', $task)
                    <a href="{{ route('tasks.edit', $task) }}"
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit Task
                    </a>
                @endcan
                <a href="{{ route('tasks.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Tasks
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $task->title }}</h1>
                                <div class="flex items-center space-x-4">
                                    {{-- Status Badge --}}
                                    @php
                                        $statusClass = match($task->status) {
                                            'completed' => 'bg-green-100 text-green-800',
                                            'in_progress' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-red-100 text-red-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                                        {{ ucwords(str_replace('_', ' ', $task->status)) }}
                                    </span>

                                    {{-- Priority Badge --}}
                                    @if($task->priority)
                                        @php
                                            $priorityClass = match($task->priority) {
                                                'urgent' => 'bg-red-100 text-red-800',
                                                'high' => 'bg-orange-100 text-orange-800',
                                                'medium' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-green-100 text-green-800'
                                            };
                                        @endphp
                                        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $priorityClass }}">
                                            {{ ucfirst($task->priority) }} Priority
                                        </span>
                                    @endif

                                    {{-- Priority Score --}}
                                    @if($task->priority_score)
                                        @php
                                            $scoreColor = match(true) {
                                                $task->priority_score >= 8 => 'text-red-600',
                                                $task->priority_score >= 6 => 'text-orange-600',
                                                $task->priority_score >= 4 => 'text-yellow-600',
                                                default => 'text-green-600'
                                            };
                                        @endphp
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Score:</span>
                                            <span class="text-lg font-bold {{ $scoreColor }}">
                                                {{ number_format($task->priority_score, 1) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Quick Actions --}}
                            <div class="flex space-x-2">
                                @can('update', $task)
                                    <button onclick="toggleTaskStatus({{ $task->id }})"
                                            class="px-4 py-2 text-sm font-medium text-white rounded-md
                                            {{ $task->status === 'completed' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        {{ $task->status === 'completed' ? 'Reopen Task' : 'Mark Complete' }}
                                    </button>
                                @endcan

                                @can('delete', $task)
                                    <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                          onsubmit="return confirm('Are you sure?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                                            Delete
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>

                    {{-- Task Details Grid --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        {{-- Left Column --}}
                        <div class="space-y-6">
                            @if($task->description)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $task->description }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($task->urgency && $task->impact && $task->effort)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-3">Priority Scoring</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        {{-- Progress Bars --}}
                                        @foreach (['urgency' => 'blue', 'impact' => 'green', 'effort' => 'red'] as $key => $color)
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm text-gray-600">{{ ucfirst($key) }}:</span>
                                                <div class="flex items-center space-x-2">
                                                    <div class="w-32 bg-gray-200 rounded-full h-2">
                                                        <div class="bg-{{ $color }}-600 h-2 rounded-full"
                                                             style="width: {{ ($task->$key / 10) * 100 }}%"></div>
                                                    </div>
                                                    <span class="text-sm font-medium w-8">{{ $task->$key }}/10</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="pt-3 mt-3 border-t border-gray-200">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-700">Calculated Score:</span>
                                                <span class="text-lg font-bold text-blue-600">
                                                    {{ number_format($task->priority_score, 2) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Formula: ({{ $task->urgency }} × {{ $task->impact }}) ÷ {{ $task->effort }} = {{ number_format($task->priority_score, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Right Column --}}
                        <div class="space-y-6">
                            {{-- Info --}}
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Task Information</h3>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Category:</span><span class="text-sm font-medium">{{ $task->category->name ?? 'No category' }}</span></div>
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Due Date:</span>
                                        <span class="text-sm font-medium">
                                            @if($task->due_date)
                                                <span class="@if($task->due_date < now() && $task->status !== 'completed') text-red-600 @endif">
                                                    {{ $task->due_date->format('F j, Y') }}
                                                    @if($task->due_date < now() && $task->status !== 'completed')
                                                        <span class="text-xs">(Overdue)</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400">No due date set</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Assigned To:</span><span class="text-sm font-medium">{{ $task->assignedTo->name ?? 'Unassigned' }}</span></div>
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Created By:</span><span class="text-sm font-medium">{{ $task->createdBy->name ?? 'Unknown' }}</span></div>
                                </div>
                            </div>

                            {{-- Timeline --}}
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-3">Timeline</h3>
                                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Created:</span><span class="text-sm font-medium">{{ $task->created_at->format('F j, Y g:i A') }}</span></div>
                                    <div class="flex justify-between"><span class="text-sm text-gray-600">Last Updated:</span><span class="text-sm font-medium">{{ $task->updated_at->format('F j, Y g:i A') }}</span></div>
                                    @if($task->status === 'completed')
                                        <div class="flex justify-between"><span class="text-sm text-gray-600">Completed:</span><span class="text-sm font-medium text-green-600">{{ $task->completed_at?->format('F j, Y g:i A') ?? $task->updated_at->format('F j, Y g:i A') }}</span></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div> <!-- end content -->
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        async function toggleTaskStatus(taskId) {
            try {
                const response = await fetch(`/tasks/${taskId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                const result = await response.json();
                if (result.success) {
                    window.location.reload();
                } else {
                    alert('Failed to update task status');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the task status');
            }
        }
    </script>
</x-app-layout>
