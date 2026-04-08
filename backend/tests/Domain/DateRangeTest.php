<?php

declare(strict_types=1);

namespace Tests\Domain;

use App\Domain\DateRange;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DateRangeTest extends TestCase
{
    public function testOverlapDaysIncludesEndDate(): void
    {
        $left = new DateRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-10'));
        $right = new DateRange(new DateTimeImmutable('2020-01-05'), new DateTimeImmutable('2020-01-07'));

        self::assertSame(3, $left->overlapDays($right));
    }

    public function testOverlapDaysReturnsZeroWhenNoOverlap(): void
    {
        $left = new DateRange(new DateTimeImmutable('2020-01-01'), new DateTimeImmutable('2020-01-02'));
        $right = new DateRange(new DateTimeImmutable('2020-01-03'), new DateTimeImmutable('2020-01-04'));

        self::assertSame(0, $left->overlapDays($right));
    }
}
