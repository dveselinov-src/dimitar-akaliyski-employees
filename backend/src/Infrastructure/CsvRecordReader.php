<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Application\RecordReaderInterface;
use App\Application\ValidationException;
use App\Domain\DateRange;
use App\Domain\WorkRecord;
use DateTimeImmutable;
use League\Csv\Reader;
use Throwable;

final class CsvRecordReader implements RecordReaderInterface
{
    /**
     * @return array<int, WorkRecord>
     */
    public function read(string $path): array
    {
        try {
            $csv = Reader::createFromPath($path);
            $csv->setHeaderOffset(0);
            $this->assertHeaders($csv->getHeader());

            $records = [];
            foreach ($csv->getRecords() as $record) {
                $records[] = $this->toWorkRecord($record);
            }

            return $records;
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new ValidationException($exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @param array<int, string> $headers
     */
    private function assertHeaders(array $headers): void
    {
        $requiredHeaders = ['EmpID', 'ProjectID', 'DateFrom', 'DateTo'];
        if (!array_diff($requiredHeaders, $headers)) {
            return;
        }

        throw new ValidationException('Invalid CSV headers! Expected format: EmpID, ProjectID, DateFrom, DateTo');
    }

    /**
     * @param array<string, mixed> $record
     */
    private function toWorkRecord(array $record): WorkRecord
    {
        $empId = (string) ($record['EmpID'] ?? '');
        $projectId = (string) ($record['ProjectID'] ?? '');
        $dateFrom = (string) ($record['DateFrom'] ?? '');
        $dateToRaw = (string) ($record['DateTo'] ?? '');

        if (!is_numeric($empId) || !is_numeric($projectId) || strtotime($dateFrom) === false) {
            throw new ValidationException('Invalid data format in CSV! Expected EmpID INT, ProjectID INT, DateFrom DATE, DateTo NULL or DATE');
        }

        if ($dateToRaw !== '' && $dateToRaw !== 'NULL' && strtotime($dateToRaw) === false) {
            throw new ValidationException('Invalid data format in CSV! Expected EmpID INT, ProjectID INT, DateFrom DATE, DateTo NULL or DATE');
        }

        $dateTo = ($dateToRaw === '' || $dateToRaw === 'NULL') ? date('Y-m-d') : $dateToRaw;

        return new WorkRecord(
            (int) $empId,
            (int) $projectId,
            new DateRange(new DateTimeImmutable($dateFrom), new DateTimeImmutable($dateTo))
        );
    }
}
