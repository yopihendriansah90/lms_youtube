<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('program.title')
                    ->label('Kelas Materi')
                    ->placeholder('-'),
                TextEntry::make('mentor.name')
                    ->label('Mentor')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Materi Kelas'),
                TextEntry::make('slug')
                    ->label('Slug'),
                TextEntry::make('excerpt')
                    ->label('Ringkasan Materi')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('Deskripsi Materi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('thumbnail')
                    ->label('Thumbnail')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Status'),
                TextEntry::make('visibility')
                    ->label('Visibilitas'),
                TextEntry::make('access_type')
                    ->label('Akses Materi'),
                TextEntry::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
                TextEntry::make('currency')
                    ->label('Mata Uang'),
                IconEntry::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextEntry::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('sort_order')
                    ->label('Urutan')
                    ->numeric(),
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
