<?php

use Illuminate\Support\Facades\Route;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

Route::get('/documents/download/{document}', function (Document $document) {
    // Check if file exists
    if (!Storage::exists($document->path)) {
        // Generate a simple text file as fallback
        $content = "Dokumen {$document->jenis_dokumen} untuk aktivitas ID: {$document->activity_id}\n";
        $content .= "Filename: {$document->filename}\n";
        $content .= "Generated at: " . now()->format('Y-m-d H:i:s');
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . ($document->filename ?? 'document.txt') . '"');
    }
    
    return Storage::download($document->path, $document->filename);
})->name('documents.download');
