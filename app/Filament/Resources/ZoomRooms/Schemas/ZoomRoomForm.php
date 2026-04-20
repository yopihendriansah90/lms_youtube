<?php

namespace App\Filament\Resources\ZoomRooms\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ZoomRoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Room Zoom')
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
                            ->label('Judul Sesi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Akses Sesi Zoom')
                    ->schema([
                        TextInput::make('join_url')
                            ->label('Link Zoom')
                            ->url()
                            ->required(),
                        TextInput::make('meeting_id')
                            ->label('Meeting ID')
                            ->maxLength(100),
                        TextInput::make('passcode')
                            ->label('Passcode')
                            ->maxLength(100),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Sesi')
                            ->seconds(false),
                        DateTimePicker::make('ends_at')
                            ->label('Berakhir Sesi')
                            ->seconds(false),
                        Select::make('status')
                            ->label('Status Sesi')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'live' => 'Live',
                                'finished' => 'Finished',
                            ])
                            ->default('scheduled')
                            ->required(),
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
