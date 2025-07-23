<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Strategies\Scoring\ScoringContext;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'urgency',
        'impact',
        'effort',
        'priority_score',
        'due_date',
        'status',
        'assigned_to',
        'category_id',
    ];

    protected $casts = [
        'due_date' => 'date',
        'priority_score' => 'decimal:2',
        'urgency' => 'integer',
        'impact' => 'integer',
        'effort' => 'integer',
    ];

    /**
     * Calculate priority score using strategy pattern.
     */
    public function calculateAndSetScore(): void
    {
        $context = new ScoringContext(); // You can inject different strategy if needed
        $this->priority_score = $context->calculateScore($this->urgency, $this->impact, $this->effort);
    }

    // Relationships
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Query Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDueDate($query, $date)
    {
        return $query->whereDate('due_date', $date);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeHighPriority($query)
    {
        return $query->orderBy('priority_score', 'desc');
    }
}
