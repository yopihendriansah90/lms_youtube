<?php

namespace App\Filament\Resources\PdfDocuments;

use App\Filament\Resources\PdfDocuments\Pages\CreatePdfDocument;
use App\Filament\Resources\PdfDocuments\Pages\EditPdfDocument;
use App\Filament\Resources\PdfDocuments\Pages\ListPdfDocuments;
use App\Filament\Resources\PdfDocuments\Pages\ViewPdfDocument;
use App\Filament\Resources\PdfDocuments\Schemas\PdfDocumentForm;
use App\Filament\Resources\PdfDocuments\Schemas\PdfDocumentInfolist;
use App\Filament\Resources\PdfDocuments\Tables\PdfDocumentsTable;
use App\Models\PdfDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PdfDocumentResource extends Resource
{
    protected static ?string $model = PdfDocument::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Dokumen PDF';

    protected static ?string $modelLabel = 'Dokumen PDF';

    protected static ?string $pluralModelLabel = 'Dokumen PDF';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Belajar';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PdfDocumentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PdfDocumentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PdfDocumentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPdfDocuments::route('/'),
            'create' => CreatePdfDocument::route('/create'),
            'view' => ViewPdfDocument::route('/{record}'),
            'edit' => EditPdfDocument::route('/{record}/edit'),
        ];
    }
}
