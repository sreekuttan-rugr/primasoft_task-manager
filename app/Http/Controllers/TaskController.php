<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TaskController extends Controller
{
    private TaskService $taskService;
    private CategoryService $categoryService;

    public function __construct(TaskService $taskService, CategoryService $categoryService)
    {
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
        
        $this->middleware('auth');
    }

    /**
     * Display a listing of tasks
     */
    public function index(Request $request)
{
    // Extract filters from request
    $filters = $this->buildFilters($request);

    // Add default values to ensure Blade won't error on missing keys
    $filters = array_merge([
        'search' => '',
        'status' => '',
        'category_id' => '',
        'assigned_to' => '',
        'due_date_from' => '',
        'due_date_to' => '',
        'sort_by' => 'priority_score',
        'sort_direction' => 'desc',
        'priority' => '',
    ], $filters);

    // Get filtered & paginated tasks
    $tasks = $this->taskService->getAllTasks($filters, 15);

    // Get all categories for filter dropdown
    $categories = $this->categoryService->getAllCategories();

    // Pass data to view
    return view('tasks.index', compact('tasks', 'categories', 'filters'));
}


    /**
     * Show the form for creating a new task
     */
    public function create()
    {
        $this->authorize('create', \App\Models\Task::class);
        
        $categories = $this->categoryService->getAllCategories();
        $users = User::all(); // Fetch all users for assignment dropdown
        return view('tasks.create', compact('categories' , 'users'));
    }

    /**
     * Store a newly created task in storage
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', \App\Models\Task::class);

        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['assigned_to'] = $data['assigned_to'] ?: Auth::id(); // Default to self
        $data['priority_score'] = ($data['urgency'] * $data['impact']) / $data['effort'];
        
        $task = $this->taskService->createTask($data);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified task
     */
    public function show($id)
    {
        $task = $this->taskService->getTaskById($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found.');
        }

        $this->authorize('view', $task);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task
     */
    public function edit($id)
    {
        $task = $this->taskService->getTaskById($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found.');
        }

       //$this->authorize('update', $task);

        $categories = $this->categoryService->getAllCategories();
        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified task in storage
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        dd('Update Task');
        $task = $this->taskService->getTaskById($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found.');
        }

        //$this->authorize('update', $task);

        $data = $request->validated();
        $this->taskService->updateTask($id, $data);

        return redirect()->route('tasks.show', $id)
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage
     */
    public function destroy($id)
    {
        $task = $this->taskService->getTaskById($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found.');
        }

        $this->authorize('delete', $task);

        $this->taskService->deleteTask($id);

        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }

    /**
     * Build filters array from request
     */
    private function buildFilters(Request $request): array
    {
        $filters = [];

        if ($request->filled('status')) {
            $filters['status'] = $request->status;
        }

        if ($request->filled('category_id')) {
            $filters['category_id'] = $request->category_id;
        }

        if ($request->filled('assigned_to')) {
            $filters['assigned_to'] = $request->assigned_to;
        }

        if ($request->filled('due_date_from')) {
            $filters['due_date_from'] = $request->due_date_from;
        }

        if ($request->filled('due_date_to')) {
            $filters['due_date_to'] = $request->due_date_to;
        }

        if ($request->filled('search')) {
            $filters['search'] = $request->search;
        }

        if ($request->filled('sort_by')) {
            $filters['sort_by'] = $request->sort_by;
        }

        if ($request->filled('sort_direction')) {
            $filters['sort_direction'] = $request->sort_direction;
        }

        if ($request->filled('priority')) {
    $filters['priority'] = $request->priority;
}

        // Default sorting by priority score if not specified
        if (!isset($filters['sort_by'])) {
            $filters['sort_by'] = 'priority_score';
            $filters['sort_direction'] = 'desc';
        }

        return $filters;
    }


    public function toggleStatus($id)
{
    $task = $this->taskService->getTaskById($id);
    if (!$task) {
        return response()->json(['error' => 'Task not found'], 404);
    }
    
    $this->authorize('update', $task);
    
    $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
    $this->taskService->updateTask($id, ['status' => $newStatus]);
    
    return response()->json(['success' => true]);
}

public function bulkUpdate(Request $request)
{
    $taskIds = $request->task_ids ?? [];
    $action = $request->action;
    
    foreach ($taskIds as $taskId) {
        $task = $this->taskService->getTaskById($taskId);
        if ($task) {
            $this->authorize('update', $task);
            
            switch ($action) {
                case 'complete':
                    $this->taskService->updateTask($taskId, ['status' => 'completed']);
                    break;
                case 'incomplete':
                    $this->taskService->updateTask($taskId, ['status' => 'pending']);
                    break;
                case 'delete':
                    $this->authorize('delete', $task);
                    $this->taskService->deleteTask($taskId);
                    break;
            }
        }
    }
    
    return redirect()->route('tasks.index')->with('success', 'Bulk action completed successfully.');
}
}