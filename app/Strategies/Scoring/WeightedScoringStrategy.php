<?php

namespace App\Strategies\Scoring;

class WeightedScoringStrategy implements ScoringStrategyInterface
{
    private float $urgencyWeight;
    private float $impactWeight;
    private float $effortWeight;

    public function __construct(float $urgencyWeight = 0.4, float $impactWeight = 0.4, float $effortWeight = 0.2)
    {
        $this->urgencyWeight = $urgencyWeight;
        $this->impactWeight = $impactWeight;
        $this->effortWeight = $effortWeight;
    }

    public function calculateScore(int $urgency, int $impact, int $effort): float
    {
        $weightedScore = ($urgency * $this->urgencyWeight) + 
                        ($impact * $this->impactWeight) - 
                        ($effort * $this->effortWeight);
        
        return round(max(0, $weightedScore), 2);
    }
    
    public function getName(): string
    {
        return 'Weighted Priority Scoring';
    }
}