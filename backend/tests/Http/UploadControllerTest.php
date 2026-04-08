<?php

declare(strict_types=1);

namespace Tests\Http;

use App\Application\FindTopPairUseCase;
use App\Domain\OverlapCalculator;
use App\Http\UploadController;
use App\Infrastructure\CsvRecordReader;
use PHPUnit\Framework\TestCase;

final class UploadControllerTest extends TestCase
{
    public function testHandleReturnsErrorWhenFileIsMissing(): void
    {
        $response = $this->invokeController(['REQUEST_METHOD' => 'POST'], []);
        self::assertSame(400, $response['code']);
        self::assertSame('No file uploaded, or corrupt file!', $response['body']['error']);
    }

    public function testHandleReturnsErrorWhenFileTypeIsInvalid(): void
    {
        $response = $this->invokeController(
            ['REQUEST_METHOD' => 'POST'],
            ['emp_coop_file' => ['type' => 'application/json', 'tmp_name' => 'x']]
        );
        self::assertSame(400, $response['code']);
        self::assertSame('Invalid file format, or corrupt file! Please upload a valid CSV file!', $response['body']['error']);
    }

    public function testHandleReturnsResultForValidCsv(): void
    {
        $path = $this->writeTempCsv(
            "EmpID,ProjectID,DateFrom,DateTo\n" .
            "1,10,2020-01-01,2020-01-05\n" .
            "2,10,2020-01-03,2020-01-10\n"
        );

        $response = $this->invokeController(
            ['REQUEST_METHOD' => 'POST'],
            ['emp_coop_file' => ['type' => 'text/csv', 'tmp_name' => $path]]
        );

        self::assertSame(200, $response['code']);
        self::assertSame(1, $response['body']['result']['emp1']);
        self::assertSame(2, $response['body']['result']['emp2']);
        self::assertSame(3, $response['body']['result']['days']);
    }

    public function testHandleReturnsNoPairError(): void
    {
        $path = $this->writeTempCsv(
            "EmpID,ProjectID,DateFrom,DateTo\n" .
            "1,10,2020-01-01,2020-01-05\n" .
            "2,11,2020-01-03,2020-01-10\n"
        );

        $response = $this->invokeController(
            ['REQUEST_METHOD' => 'POST'],
            ['emp_coop_file' => ['type' => 'text/csv', 'tmp_name' => $path]]
        );

        self::assertSame(400, $response['code']);
        self::assertSame('Data does not include pairs!', $response['body']['error']);
    }

    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $files
     * @return array{code:int, body:array<string, mixed>}
     */
    private function invokeController(array $server, array $files): array
    {
        http_response_code(200);
        $controller = new UploadController(
            new FindTopPairUseCase(new CsvRecordReader(), new OverlapCalculator())
        );

        ob_start();
        $controller->handle($server, $files);
        $json = (string) ob_get_clean();

        /** @var array<string, mixed> $body */
        $body = json_decode($json, true);

        return [
            'code' => (int) http_response_code(),
            'body' => $body,
        ];
    }

    private function writeTempCsv(string $content): string
    {
        $path = tempnam(sys_get_temp_dir(), 'upload_controller_');
        file_put_contents($path, $content);

        return $path;
    }
}
