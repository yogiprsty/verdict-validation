<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;
use setasign\Fpdi\Fpdi;
use TCPDF;

class FileService
{
    public function upload(UploadedFile $file, string $filename, string $folder): string
    {
        $filename = $filename . '.' . $file->getClientOriginalExtension();

        $filePath = $file->storeAs($folder, $filename, 'public');

        return $filePath;
    }

    public function delete(string $originalFilePath, string $stampedFilePath)
    {
        $fileInfo = pathinfo($originalFilePath);
        $qrCodePath = 'qr_codes/' . $fileInfo['filename'] . '.png';
        Storage::disk('public')->delete([$originalFilePath, $qrCodePath, $stampedFilePath]);
    }

    public function generateQrCode(string $inputString, string $uuid): string
    {
        QRCode::text($inputString)
            ->setOutfile(Storage::disk('public')->path('/qr_codes/' . $uuid . '.png'))
            ->png();

        return 'qr_codes/' . $uuid . '.png';
    }

    public function stampPdfWithQrCode($inputPdfPath, $qrCodePath): string
    {
        $pdf = new Fpdi();

        // Define the full paths to the input and QR code files
        $inputPdfPathOnStorage = storage_path('app/public/' . $inputPdfPath);
        $qrCodePathOnStorage = storage_path('app/public/' . $qrCodePath);

        // Set up the source file (input PDF)
        $pageCount = $pdf->setSourceFile($inputPdfPathOnStorage);

        // Ensure the output directory exists
        $outputDir = storage_path('app/public/stamped_verdicts');
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);  // Create the directory with proper permissions
        }

        // Loop through each page of the input PDF
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();

            $pdf->SetFont('Times', 'B', 70);
            $pdf->SetTextColor(230, 230, 230);
            $watermarkText = 'PA TONDANO';
            FileService::addWatermark(100, 150, $watermarkText, 45, $pdf);
            $pdf->SetXY(25, 25);

            $tplId = $pdf->importPage($pageNo);
            $pdf->useTemplate($tplId, 0, 0);

            // Set QR code position and size
            $qrX = 10;  // X position on the page
            $qrY = 10;  // Y position on the page
            $qrWidth = 30; // Width of QR code in mm

            // Stamp QR code on the page
            $pdf->Image($qrCodePathOnStorage, $qrX, $qrY, $qrWidth);
        }

        // Define the output file path
        $outputPdfPath = 'stamped_verdicts/stamped_' . basename($inputPdfPath);
        $outputFilePath = storage_path('app/public/' . $outputPdfPath);

        // Output the new PDF to the specified file path
        $pdf->Output('F', $outputFilePath);

        // Return the output path
        return $outputPdfPath;
    }

    function addWatermark($x, $y, $watermarkText, $angle, $pdf)
    {
        $angle = $angle * M_PI / 180;
        $c = cos($angle);
        $s = sin($angle);
        $x_original = $x;

        for ($i = 0; $i < 4; $i++) {
            $cx = $x;
            $cy = $y;

            $pdf->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
            $pdf->Text($x, $y, $watermarkText);
            $pdf->_out('Q');
            $x -= 50;
            $y += 75;
        }
    }
}
