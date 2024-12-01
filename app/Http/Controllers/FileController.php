<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function downloadOriginal($uuid)
    {
        // Path to the file in storage
        $filename = "verdicts/{$uuid}.pdf";
        $filePath = storage_path("app/public/{$filename}");
        
        // Check if the file exists
        // if (!Storage::disk('public')->exists($filename)) {
        //     abort(404, 'File not found');
        // }

        // Return the file for download
        return response()->download($filePath);
    }

    public function downloadStamped($uuid)
    {
        // Path to the file in storage
        $filename = "stamped_verdicts/stamped_{$uuid}.pdf";
        $filePath = storage_path("app/public/{$filename}");
        
        // Check if the file exists
        // if (!Storage::disk('public')->exists($filename)) {
        //     abort(404, 'File not found');
        // }

        // Return the file for download
        return response()->download($filePath);
    }
}
