<?php

namespace App\Strategies\Scoring;

interface ScoringStrategyInterface
{
    public function calculateScore(int $urgency, int $impact, int $effort): float;
    
    public function getName(): string;
}