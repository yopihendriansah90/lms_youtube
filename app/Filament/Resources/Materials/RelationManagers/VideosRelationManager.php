<?php

namespace App\Filament\Resources\Materials\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                Section::make('Video Materi')
                    ->schema([
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
                            ->maxLength(32),
                        Select::make('section_id')
                            ->label('Bagian Materi')
                            ->relationship('section', 'title')
                            ->searchable()
                            ->preload(),
                        TextInput::make('duration_in_seconds')
                            ->label('Durasi (detik)')
                            ->numeric(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
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
                            ->label('Preview')
                            ->default(false),
                        Toggle::make('is_published')
                            ->label('Publikasikan')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Video')
                    ->searchable(),
                TextColumn::make('youtube_video_id')
                    ->label('Video ID'),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge(),
                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
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
