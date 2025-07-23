<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ImportCsvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // Only authenticated users can import
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'csv_file' => [
                'required',
                File::types(['csv', 'txt'])
                    ->max(5 * 1024) // 5MB max
                    ->rules(['mimes:csv,txt,plain']),
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'csv_file.required' => 'Please select a CSV file to import.',
            'csv_file.mimes' => 'The file must be a CSV file (.csv or .txt).',
            'csv_file.max' => 'The file size must not exceed 5MB.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'csv_file' => 'CSV file',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('csv_file')) {
                $file = $this->file('csv_file');
                
                // Additional validation for file content
                if (!$this->isValidCsvStructure($file)) {
                    $validator->errors()->add('csv_file', 'The CSV file structure is invalid.');
                }
            }
        });
    }

    /**
     * Validate basic CSV structure
     */
    private function isValidCsvStructure($file): bool
    {
        try {
            $handle = fopen($file->getRealPath(), 'r');
            
            if (!$handle) {
                return false;
            }
            
            // Read the first line (headers)
            $headers = fgetcsv($handle);
            fclose($handle);
            
            // Check if we have headers
            if (!$headers || empty($headers)) {
                return false;
            }
            
            // Check for required headers
            $requiredHeaders = ['title']; // At minimum, we need a title
            $headerLower = array_map('strtolower', array_map('trim', $headers));
            
            foreach ($requiredHeaders as $required) {
                if (!in_array(strtolower($required), $headerLower)) {
                    return false;
                }
            }
            
            return true;
            
        } catch (\Exception $e) {
            return false;
        }
    }
}