<?php

declare(strict_types=1);

namespace App\Domain;

final class OverlapCalculator implements OverlapCalculatorInterface
{
    public function calculateTopPair(array $records): ?EmployeePairResult
    {
        $employeeProjects = $this->groupByEmployeeAndProject($records);
        $pairCoopDays = [];

        foreach ($employeeProjects as $emp1 => $projects1) {
            foreach ($employeeProjects as $emp2 => $projects2) {
                if ($emp1 >= $emp2) {
                    continue;
                }

                foreach ($projects1 as $projectId => $ranges1) {
                    if (!isset($projects2[$projectId])) {
                        continue;
                    }

                    foreach ($ranges1 as $range1) {
                        foreach ($projects2[$projectId] as $range2) {
                            $days = $range1->overlapDays($range2);
                            if ($days === 0) {
                                continue;
                            }

                            $pairKey = $emp1 . '-' . $emp2;
                            if (!isset($pairCoopDays[$pairKey])) {
                                $pairCoopDays[$pairKey] = [
                                    'emp1' => $emp1,
                                    'emp2' => $emp2,
                                    'days' => 0,
                                    'commonProjects' => [],
                                ];
                            }

                            $pairCoopDays[$pairKey]['days'] += $days;
                            $pairCoopDays[$pairKey]['commonProjects'][] = [$emp1, $emp2, (int) $projectId, $days];
                        }
                    }
                }
            }
        }

        return $this->topPairFrom($pairCoopDays);
    }

    /**
     * @param array<int, WorkRecord> $records
     * @return array<int, array<int, array<int, DateRange>>>
     */
    private function groupByEmployeeAndProject(array $records): array
    {
        $grouped = [];
        foreach ($records as $record) {
            $grouped[$record->employeeId()][$record->projectId()][] = $record->dateRange();
        }

        return $grouped;
    }

    /**
     * @param array<string, array{emp1:int,emp2:int,days:int,commonProjects:array<int, array{0:int,1:int,2:int,3:int}>}> $pairs
     */
    private function topPairFrom(array $pairs): ?EmployeePairResult
    {
        $maxDays = 0;
        $result = null;

        foreach ($pairs as $pair) {
            if ($pair['days'] <= $maxDays) {
                continue;
            }

            $maxDays = $pair['days'];
            $result = new EmployeePairResult(
                $pair['emp1'],
                $pair['emp2'],
                $pair['days'],
                $pair['commonProjects']
            );
        }

        return $result;
    }
}
