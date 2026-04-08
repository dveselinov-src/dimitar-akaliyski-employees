<?php

declare(strict_types=1);

namespace App\Domain;

interface OverlapCalculatorInterface
{
    /**
     * @param array<int, WorkRecord> $records
     */
    public function calculateTopPair(array $records): ?EmployeePairResult;
}
