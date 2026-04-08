<?php

declare(strict_types=1);

namespace App\Domain;

final class WorkRecord
{
    public function __construct(
        private readonly int $employeeId,
        private readonly int $projectId,
        private readonly DateRange $dateRange
    ) {
    }

    public function employeeId(): int
    {
        return $this->employeeId;
    }

    public function projectId(): int
    {
        return $this->projectId;
    }

    public function dateRange(): DateRange
    {
        return $this->dateRange;
    }
}
