<?php

namespace App\Filament\Resources\Materials\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialUpdatesRelationManager extends RelationManager
{
    protected static string $relationship = 'updates';

    protected static ?string $title = 'Update Materi';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Update Materi')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Update')
                            ->required()
                            ->maxLength(255),
                        Select::make('update_type')
                            ->label('Tipe Update')
                            ->options([
                                'info' => 'Informasi',
                                'announcement' => 'Pengumuman',
                                'release' => 'Rilis Baru',
                                'assignment' => 'Tugas',
                            ])
                            ->default('info')
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        RichEditor::make('content')
                            ->label('Isi Update')
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('attachments')
                            ->label('Lampiran Update')
                            ->collection('attachments')
                            ->multiple()
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        Placeholder::make('attachment_hint')
                            ->label('Catatan')
                            ->content('Tambahkan file pendukung update materi langsung dari halaman materi ini.')
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
                    ->label('Judul Update')
                    ->searchable(),
                TextColumn::make('update_type')
                    ->label('Tipe')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
