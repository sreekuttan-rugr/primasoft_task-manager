<?php

namespace App\Strategies\Scoring;

class ScoringContext
{
    private ScoringStrategyInterface $strategy;

    public function __construct(ScoringStrategyInterface $strategy = null)
    {
        $this->strategy = $strategy ?: new DefaultScoringStrategy();
    }

    public function setStrategy(ScoringStrategyInterface $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function calculateScore(int $urgency, int $impact, int $effort): float
    {
        return $this->strategy->calculateScore($urgency, $impact, $effort);
    }

    public function getStrategyName(): string
    {
        return $this->strategy->getName();
    }
}