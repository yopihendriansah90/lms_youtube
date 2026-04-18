<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video Pembelajaran')
                    ->schema([
                        Select::make('material_id')
                            ->label('Materi')
                            ->relationship('material', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('section_id')
                            ->label('Bagian Materi')
                            ->relationship('section', 'title')
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->label('Judul Video')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('youtube_url')
                            ->label('URL YouTube')
                            ->url()
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (blank($state)) {
                                    return;
                                }

                                preg_match('/(?:youtu\.be\/|v=|embed\/)([\w-]{11})/', $state, $matches);

                                if (isset($matches[1])) {
                                    $set('youtube_video_id', $matches[1]);
                                }
                            }),
                        TextInput::make('youtube_video_id')
                            ->label('YouTube Video ID')
                            ->required()
                            ->maxLength(32)
                            ->helperText('Akan diisi otomatis jika URL valid.'),
                        TextInput::make('duration_in_seconds')
                            ->label('Durasi (detik)')
                            ->numeric(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Akses dan Publish')
                    ->schema([
                        Select::make('access_type')
                            ->label('Tipe Akses')
                            ->options([
                                'free' => 'Gratis',
                                'paid' => 'Berbayar',
                            ])
                            ->default('free')
                            ->required(),
                        TextInput::make('price')
                            ->label('Harga Unlock')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        Toggle::make('is_preview')
                            ->label('Video Preview')
                            ->default(false)
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
