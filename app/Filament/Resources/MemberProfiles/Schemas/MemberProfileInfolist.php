<?php

namespace App\Filament\Resources\MemberProfiles\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MemberProfileInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('user.name')
                    ->label('Nama Member'),
                TextEntry::make('user.email')
                    ->label('Email'),
                TextEntry::make('phone')
                    ->label('WhatsApp')
                    ->placeholder('-'),
                TextEntry::make('avatar')
                    ->label('Avatar')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->label('Kota')
                    ->placeholder('-'),
                TextEntry::make('province')
                    ->label('Provinsi')
                    ->placeholder('-'),
                TextEntry::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('gender')
                    ->label('Gender')
                    ->placeholder('-'),
                TextEntry::make('occupation')
                    ->label('Pekerjaan')
                    ->placeholder('-'),
                TextEntry::make('bio')
                    ->label('Bio')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextEntry::make('joined_at')
                    ->label('Tanggal Bergabung')
                    ->dateTime()
                    ->placeholder('-'),
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
