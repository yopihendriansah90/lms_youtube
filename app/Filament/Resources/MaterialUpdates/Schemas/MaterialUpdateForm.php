<?php

namespace App\Filament\Resources\MaterialUpdates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MaterialUpdateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Update')
                    ->schema([
                        Select::make('material_id')
                            ->label('Materi')
                            ->relationship('material', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                            ->default(false)
                            ->required(),
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
                            ->content('Lampiran update dikelola melalui Filament Spatie Media Library agar file pendukung update tersimpan terpusat.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
