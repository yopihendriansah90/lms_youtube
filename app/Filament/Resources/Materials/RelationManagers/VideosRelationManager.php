<?php

namespace App\Filament\Resources\Materials\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VideosRelationManager extends RelationManager
{
    protected static string $relationship = 'videos';

    protected static ?string $title = 'Video YouTube';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video Pembelajaran')
                    ->description('Tambahkan video YouTube yang menjadi isi utama materi ini.')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Video')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Cara Menentukan Niche Channel')
                            ->helperText('Judul ini akan tampil pada daftar video di halaman detail materi.'),
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
                            ->helperText('Tempel link YouTube, lalu sistem akan mengisi Video ID secara otomatis.'),
                        Hidden::make('youtube_video_id')
                            ->required(),
                        TextInput::make('duration_in_seconds')
                            ->label('Durasi (detik)')
                            ->numeric()
                            ->helperText('Opsional. Cocok diisi jika ingin menampilkan durasi video secara akurat.'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->helperText('Jelaskan inti pembahasan video ini secara singkat.')
                            ->columnSpanFull(),
                        Select::make('access_type')
                            ->label('Akses Video')
                            ->options([
                                'free' => 'Free',
                                'paid' => 'Premium',
                            ])
                            ->default('free')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                $set('price', 0);
                            })
                            ->helperText('Gunakan Free untuk video terbuka dan Premium untuk video yang mengikuti aturan akses materi.'),
                        Hidden::make('price')
                            ->default(0)
                            ->dehydrated(true),
                        Toggle::make('is_preview')
                            ->label('Preview')
                            ->default(false)
                            ->helperText('Aktifkan jika video ini boleh ditonton sebagai cuplikan walau akses premium.'),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false)
                            ->helperText('Video hanya tampil di portal member jika sudah dipublikasikan.'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Video')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Angka lebih kecil akan ditampilkan lebih dulu.'),
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
                    ->label('Judul Video')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'paid' ? 'warning' : 'success'),
                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tayang')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Video'),
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
