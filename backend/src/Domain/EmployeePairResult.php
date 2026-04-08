<?php

declare(strict_types=1);

namespace App\Domain;

final class EmployeePairResult
{
    /**
     * @param array<int, array{0:int,1:int,2:int,3:int}> $commonProjects
     */
    public function __construct(
        private readonly int $emp1,
        private readonly int $emp2,
        private readonly int $days,
        private readonly array $commonProjects
    ) {
    }

    public function emp1(): int
    {
        return $this->emp1;
    }

    public function emp2(): int
    {
        return $this->emp2;
    }

    public function days(): int
    {
        return $this->days;
    }

    /**
     * @return array<int, array{0:int,1:int,2:int,3:int}>
     */
    public function commonProjects(): array
    {
        return $this->commonProjects;
    }

    /**
     * @return array{emp1:int,emp2:int,days:int,commonProjects:array<int, array{0:int,1:int,2:int,3:int}>}
     */
    public function toArray(): array
    {
        return [
            'emp1' => $this->emp1,
            'emp2' => $this->emp2,
            'days' => $this->days,
            'commonProjects' => $this->commonProjects,
        ];
    }
}
