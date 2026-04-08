<?php

declare(strict_types=1);

namespace App\Http;

use App\Application\FindTopPairUseCase;
use App\Application\NoPairFoundException;
use App\Application\ValidationException;
use Throwable;

final class UploadController
{
    public function __construct(private readonly FindTopPairUseCase $useCase)
    {
    }

    /**
     * @param array<string, mixed> $server
     * @param array<string, mixed> $files
     */
    public function handle(array $server, array $files): void
    {
        if (($server['REQUEST_METHOD'] ?? '') !== 'POST') {
            JsonResponse::send(405, ['error' => 'Method not allowed']);
            return;
        }

        if (!isset($files['emp_coop_file'])) {
            JsonResponse::send(400, ['error' => 'No file uploaded, or corrupt file!']);
            return;
        }

        $file = $files['emp_coop_file'];
        $fileType = (string) ($file['type'] ?? '');
        $tmpPath = (string) ($file['tmp_name'] ?? '');
        if ($fileType !== 'text/csv' || $tmpPath === '') {
            JsonResponse::send(400, ['error' => 'Invalid file format, or corrupt file! Please upload a valid CSV file!']);
            return;
        }

        try {
            $result = $this->useCase->execute($tmpPath);
            JsonResponse::send(200, ['result' => $result->toArray()]);
        } catch (ValidationException | NoPairFoundException $exception) {
            JsonResponse::send(400, ['error' => $exception->getMessage()]);
        } catch (Throwable $exception) {
            JsonResponse::send(400, ['error' => $exception->getMessage()]);
        }
    }
}
