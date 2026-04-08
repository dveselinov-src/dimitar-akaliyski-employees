<?php

declare(strict_types=1);

namespace App\Domain;

use DateTimeImmutable;

final class DateRange
{
    public function __construct(
        private readonly DateTimeImmutable $from,
        private readonly DateTimeImmutable $to
    ) {
    }

    public function from(): DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): DateTimeImmutable
    {
        return $this->to;
    }

    public function overlapDays(self $other): int
    {
        $start = max($this->from->getTimestamp(), $other->from->getTimestamp());
        $end = min($this->to->getTimestamp(), $other->to->getTimestamp());
        if ($start > $end) {
            return 0;
        }

        return (int) floor(($end - $start) / 86400) + 1;
    }
}
