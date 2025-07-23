<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Imports\TasksImport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportController extends Controller
{
    // GET /import
    public function index()
    {
        return view('import.index');
    }

    // POST /import/csv
    public function importCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        try {
            Excel::import(new TasksImport, $request->file('csv_file'));

            return redirect()->back()->with('success', 'Tasks imported successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    // GET /import/template
    public function downloadTemplate(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv'];
        $columns = ['title', 'description', 'category', 'due_date', 'status'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Optional: sample row
            fputcsv($file, ['Sample Task', 'This is a sample', 'Development', '2025-08-01', 'pending']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers)
            ->withHeaders(['Content-Disposition' => 'attachment; filename="task_import_template.csv"']);
    }
}
