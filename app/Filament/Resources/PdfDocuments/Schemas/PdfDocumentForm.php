<?php

namespace App\Filament\Resources\PdfDocuments\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PdfDocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Dokumen Materi')
                    ->schema([
                        Select::make('material_id')
                            ->label('Materi')
                            ->relationship('material', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('title')
                            ->label('Judul Dokumen')
                            ->required()
                            ->maxLength(255),
                        Hidden::make('access_type')
                            ->default('free')
                            ->dehydrated(true),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->required(),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('document_file')
                            ->label('File PDF')
                            ->collection('documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxFiles(1)
                            ->required()
                            ->columnSpanFull(),
                        Placeholder::make('upload_hint')
                            ->label('Catatan')
                            ->content('Upload file PDF melalui field di atas. File akan disimpan menggunakan Spatie Media Library.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
