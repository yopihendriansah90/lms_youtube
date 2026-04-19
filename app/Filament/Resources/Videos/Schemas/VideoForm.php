<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class VideoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video Materi Kelas')
                    ->schema([
                        Select::make('material_id')
                            ->label('Materi Kelas')
                            ->relationship('material', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),
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
                        Hidden::make('youtube_video_id')
                            ->required(),
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
                            ->label('Akses Video')
                            ->options([
                                'free' => 'Free',
                                'paid' => 'Premium',
                            ])
                            ->default('free')
                            ->required()
                            ->helperText('Gunakan Free untuk video terbuka dan Premium untuk video yang mengikuti aturan akses materi.'),
                        Hidden::make('price')
                            ->default(0)
                            ->dehydrated(true),
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
