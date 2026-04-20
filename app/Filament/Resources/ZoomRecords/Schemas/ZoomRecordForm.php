<?php

namespace App\Filament\Resources\ZoomRecords\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ZoomRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rekaman Zoom')
                    ->description('Isi detail utama rekaman Zoom yang akan tampil di portal member.')
                    ->schema([
                        Select::make('program_id')
                            ->label('Kelas Materi')
                            ->relationship('program', 'title')
                            ->searchable()
                            ->preload(),
                        Select::make('mentor_id')
                            ->label('Mentor')
                            ->relationship('mentor', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->label('Judul Rekaman')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Weekly Strategy Alignment Q3')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(5)
                            ->placeholder('Jelaskan isi rekaman, fokus pembahasan, dan manfaat untuk member.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Sumber Video')
                    ->description('Gunakan link YouTube untuk embed video di halaman member.')
                    ->schema([
                        TextInput::make('youtube_url')
                            ->label('URL YouTube')
                            ->url()
                            ->required()
                            ->placeholder('https://www.youtube.com/watch?v=...')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if (blank($state)) {
                                    return;
                                }

                                preg_match('/(?:youtu\.be\/|v=|embed\/)([\w-]{11})/', $state, $matches);

                                if (isset($matches[1])) {
                                    $set('youtube_video_id', $matches[1]);
                                }
                            })
                            ->helperText('Tempel link YouTube dan sistem akan mengisi Video ID secara otomatis.'),
                        Hidden::make('youtube_video_id')
                            ->required(),
                        TextInput::make('zoom_recording_url')
                            ->label('URL Rekaman Zoom Asli')
                            ->url()
                            ->placeholder('Opsional, untuk arsip internal atau referensi admin.'),
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail Cadangan')
                            ->image()
                            ->directory('zoom')
                            ->imageEditor()
                            ->helperText('Opsional. Jika kosong, thumbnail di halaman member akan mengambil otomatis dari YouTube.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Publish & Akses')
                    ->description('Atur status tayang dan apakah rekaman ini gratis atau premium.')
                    ->schema([
                        Select::make('access_type')
                            ->label('Tipe Akses')
                            ->options([
                                'free' => 'Gratis',
                                'paid' => 'Berbayar',
                            ])
                            ->default('free')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state === 'free') {
                                    $set('price', 0);
                                }
                            }),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required()
                            ->minValue(0)
                            ->disabled(fn (Get $get): bool => $get('access_type') !== 'paid')
                            ->dehydrated(),
                        DateTimePicker::make('recorded_at')
                            ->label('Tanggal Rekaman')
                            ->seconds(false),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->required(),
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
