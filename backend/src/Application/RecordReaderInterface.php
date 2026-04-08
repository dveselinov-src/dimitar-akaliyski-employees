<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\WorkRecord;

interface RecordReaderInterface
{
    /**
     * @return array<int, WorkRecord>
     */
    public function read(string $path): array;
}
