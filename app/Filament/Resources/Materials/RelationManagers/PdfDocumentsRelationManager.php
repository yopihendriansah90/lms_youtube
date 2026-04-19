<?php

namespace App\Filament\Resources\Materials\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PdfDocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'pdfDocuments';

    protected static ?string $title = 'Dokumen PDF';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lampiran PDF')
                    ->description('Tambahkan file PDF pendukung yang relevan dengan isi materi atau video.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Dokumen')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Worksheet Menentukan Niche Channel'),
                        Hidden::make('access_type')
                            ->default('free')
                            ->dehydrated(true),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->required()
                            ->helperText('Dokumen hanya tampil di halaman member jika dipublikasikan.'),
                        TextInput::make('sort_order')
                            ->label('Urutan Dokumen')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Angka lebih kecil akan tampil lebih dulu.'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->helperText('Jelaskan fungsi PDF ini, misalnya worksheet, rangkuman, atau template.')
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('document_file')
                            ->label('File PDF')
                            ->collection('documents')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxFiles(1)
                            ->required()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Placeholder::make('upload_hint')
                            ->label('Catatan')
                            ->content('Upload PDF langsung dari materi agar dokumen terkait tema kelas ini tersimpan rapi.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('free_access_label')
                    ->label('Akses')
                    ->badge()
                    ->state('Gratis')
                    ->color('success'),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah PDF'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
