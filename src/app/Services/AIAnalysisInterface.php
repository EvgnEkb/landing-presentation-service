<?php

namespace App\Services;

interface AIAnalysisInterface
{
    public function analyze(string $text): ?array;
}
