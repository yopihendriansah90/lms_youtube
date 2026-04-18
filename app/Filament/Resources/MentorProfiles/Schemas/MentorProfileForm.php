<?php

namespace App\Filament\Resources\MentorProfiles\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MentorProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil Mentor')
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('display_name')
                            ->label('Nama Tampil')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('speciality')
                            ->label('Spesialisasi')
                            ->maxLength(255),
                        FileUpload::make('photo')
                            ->label('Foto Mentor')
                            ->image()
                            ->disk('public')
                            ->directory('mentors/photos')
                            ->visibility('public')
                            ->imageEditor(),
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(30),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                        TextInput::make('instagram_url')
                            ->label('URL Instagram')
                            ->url()
                            ->maxLength(255),
                        TextInput::make('youtube_url')
                            ->label('URL YouTube')
                            ->url()
                            ->maxLength(255),
                        Textarea::make('short_bio')
                            ->label('Bio Singkat')
                            ->rows(3)
                            ->columnSpanFull(),
                        Textarea::make('full_bio')
                            ->label('Bio Lengkap')
                            ->rows(6)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])->columns(1);
    }
}
