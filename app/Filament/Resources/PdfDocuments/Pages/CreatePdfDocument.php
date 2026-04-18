<?php

namespace App\Filament\Resources\PdfDocuments\Pages;

use App\Filament\Resources\PdfDocuments\PdfDocumentResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePdfDocument extends CreateRecord
{
    protected static string $resource = PdfDocumentResource::class;
}
