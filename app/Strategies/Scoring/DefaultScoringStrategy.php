<?php

namespace App\Strategies\Scoring;

class DefaultScoringStrategy implements ScoringStrategyInterface
{
    public function calculateScore(int $urgency, int $impact, int $effort): float
    {
        if ($effort <= 0) {
            return 0;
        }
        
        return round(($urgency * $impact) / $effort, 2);
    }
    
    public function getName(): string
    {
        return 'Default Priority Scoring';
    }
}