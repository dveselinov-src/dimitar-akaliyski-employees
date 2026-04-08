<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\EmployeePairResult;
use App\Domain\OverlapCalculatorInterface;

final class FindTopPairUseCase
{
    public function __construct(
        private readonly RecordReaderInterface $recordReader,
        private readonly OverlapCalculatorInterface $overlapCalculator
    ) {
    }

    public function execute(string $csvPath): EmployeePairResult
    {
        $records = $this->recordReader->read($csvPath);
        $result = $this->overlapCalculator->calculateTopPair($records);

        if ($result === null) {
            throw new NoPairFoundException('Data does not include pairs!');
        }

        return $result;
    }
}
