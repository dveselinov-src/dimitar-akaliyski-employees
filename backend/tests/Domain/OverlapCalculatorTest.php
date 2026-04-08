<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\DateRange;
use App\Domain\OverlapCalculator;
use App\Domain\WorkRecord;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class OverlapCalculatorTest extends TestCase
{
    public function testCalculateTopPairReturnsExpectedPair(): void
    {
        $records = [
            new WorkRecord(1, 10, new DateRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-05'))),
            new WorkRecord(2, 10, new DateRange(new DateTimeImmutable('2020-01-03'), new DateTimeImmutable('2020-01-10'))),
            new WorkRecord(3, 11, new DateRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-02'))),
        ];

        $result = (new OverlapCalculator())->calculateTopPair($records);

        self::assertNotNull($result);
        self::assertSame(1, $result->emp1());
        self::assertSame(2, $result->emp2());
        self::assertSame(3, $result->days());
    }

    public function testCalculateTopPairReturnsNullWhenNoPairs(): void
    {
        $records = [
            new WorkRecord(1, 10, new DateRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-05'))),
            new WorkRecord(2, 11, new DateRange(new DateTimeImmutable('2020-01-03'), new DateTimeImmutable('2020-01-10'))),
        ];

        $result = (new OverlapCalculator())->calculateTopPair($records);
        self::assertNull($result);
    }
}
