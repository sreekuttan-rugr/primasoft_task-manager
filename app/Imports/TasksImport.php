<?php

namespace App\Imports;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use App\Strategies\Scoring\ScoringContext;

class TasksImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->shift();

        $scoringContext = new ScoringContext(); // You can change to another strategy if needed

        foreach ($rows as $row) {
            try {
                // Try to find or create category
                $category = Category::firstOrCreate([
                    'name' => trim($row[2]),
                ]);

                // Parse values
                $urgency = (int) $row[5];
                $impact  = (int) $row[6];
                $effort  = (int) $row[7];

                // Calculate score using the strategy
                $score = $scoringContext->calculateScore($urgency, $impact, $effort);

                Task::create([
                    'title'       => $row[0],
                    'description' => $row[1],
                    'category_id' => $category->id,
                    'due_date'    => Carbon::parse($row[3]),
                    'status'      => $row[4],
                    'urgency'     => $urgency,
                    'impact'      => $impact,
                    'effort'      => $effort,
                    'score'       => $score,
                    'assigned_to'     => Auth::id(), // or assign to admin
                ]);
            } catch (\Exception $e) {
                Log::error('Row Import Error', [
                    'row' => $row,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
