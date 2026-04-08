<?php

declare(strict_types=1);

use App\Application\FindTopPairUseCase;
use App\Domain\OverlapCalculator;
use App\Http\UploadController;
use App\Infrastructure\CsvRecordReader;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/vendor/autoload.php';

$controller = new UploadController(
    new FindTopPairUseCase(
        new CsvRecordReader(),
        new OverlapCalculator()
    )
);

$controller->handle($_SERVER, $_FILES);