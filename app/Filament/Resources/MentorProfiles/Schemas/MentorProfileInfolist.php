<?php

namespace App\Filament\Resources\MentorProfiles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MentorProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('user.name')
                    ->label('Akun User'),
                TextEntry::make('user.email')
                    ->label('Email'),
                TextEntry::make('display_name')
                    ->label('Nama Tampil'),
                TextEntry::make('speciality')
                    ->label('Spesialisasi')
                    ->placeholder('-'),
                TextEntry::make('photo')
                    ->label('Foto')
                    ->placeholder('-'),
                TextEntry::make('short_bio')
                    ->label('Bio Singkat')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('full_bio')
                    ->label('Bio Lengkap')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('instagram_url')
                    ->label('Instagram')
                    ->placeholder('-'),
                TextEntry::make('youtube_url')
                    ->label('YouTube')
                    ->placeholder('-'),
                TextEntry::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
