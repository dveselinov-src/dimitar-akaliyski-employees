<?php

declare(strict_types=1);

namespace Tests\Infrastructure;

use App\Application\ValidationException;
use App\Infrastructure\CsvRecordReader;
use PHPUnit\Framework\TestCase;

final class CsvRecordReaderTest extends TestCase
{
    public function testReadThrowsForInvalidHeaders(): void
    {
        $path = $this->writeTempCsv("A,B,C,D\n1,2,3,4\n");

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid CSV headers! Expected format: EmpID, ProjectID, DateFrom, DateTo');

        (new CsvRecordReader())->read($path);
    }

    public function testReadThrowsForInvalidDataType(): void
    {
        $path = $this->writeTempCsv("EmpID,ProjectID,DateFrom,DateTo\na,10,2020-01-01,2020-01-02\n");

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid data format in CSV! Expected EmpID INT, ProjectID INT, DateFrom DATE, DateTo NULL or DATE');

        (new CsvRecordReader())->read($path);
    }

    public function testReadParsesValidRows(): void
    {
        $path = $this->writeTempCsv("EmpID,ProjectID,DateFrom,DateTo\n1,10,2020-01-01,2020-01-02\n");
        $records = (new CsvRecordReader())->read($path);

        self::assertCount(1, $records);
        self::assertSame(1, $records[0]->employeeId());
        self::assertSame(10, $records[0]->projectId());
    }

    private function writeTempCsv(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), 'csv_test_');
        file_put_contents($path, $content);

        return $path;
    }
}
