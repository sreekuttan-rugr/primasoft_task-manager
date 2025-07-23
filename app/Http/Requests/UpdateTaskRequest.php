<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Authorization handled in controller
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
            'urgency' => 'required|integer|min:1|max:10',
            'impact' => 'required|integer|min:1|max:10',
            'effort' => 'required|integer|min:1|max:10',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The task title is required.',
            'title.max' => 'The task title cannot exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'assigned_to.exists' => 'The assigned user is invalid.',
            'urgency.required' => 'Please rate the urgency (1-10).',
            'urgency.between' => 'Urgency must be between 1 and 10.',
            'impact.required' => 'Please rate the impact (1-10).',
            'impact.between' => 'Impact must be between 1 and 10.',
            'effort.required' => 'Please estimate the effort (1-10).',
            'effort.between' => 'Effort must be between 1 and 10.',
        ];
    }
}