<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $endOfWeek = $today->copy()->addDays(7);

        $total = Task::count();
        $pending = Task::where('status', 'pending')->count();
        $completed = Task::where('status', 'completed')->count();

        $dueThisWeek = Task::whereBetween('due_date', [$today, $endOfWeek])->get();

        $topPriority = Task::orderByDesc('priority_score')
                          ->take(5)
                          ->get(['id', 'title', 'priority_score']);

        return response()->json([
            'total_tasks' => $total,
            'pending_tasks' => $pending,
            'completed_tasks' => $completed,
            'tasks_due_this_week' => $dueThisWeek,
            'top_5_high_priority_tasks' => $topPriority,
        ]);
    }
}
