<?php

namespace App\Services;

use App\Services\FileService;

$fileService = new FileService();

$fileService->stampPdfWithQrCode("storage/app/public/verdicts/eaf2abdb-6837-4283-a64f-5c205adcde87.pdf","storage/app/public/qr_codes/eaf2abdb-6837-4283-a64f-5c205adcde87.png");