<?php

namespace App\Filament\Resources\Videos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VideoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('material.program.title')
                    ->label('Kelas Materi')
                    ->placeholder('-'),
                TextEntry::make('material.title')
                    ->label('Materi Kelas'),
                TextEntry::make('section.title')
                    ->label('Bagian Materi')
                    ->placeholder('-'),
                TextEntry::make('title')
                    ->label('Judul'),
                TextEntry::make('youtube_url')
                    ->label('URL YouTube'),
                TextEntry::make('youtube_video_id')
                    ->label('Video ID'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('duration_in_seconds')
                    ->label('Durasi')
                    ->numeric()
                    ->suffix(' detik')
                    ->placeholder('-'),
                TextEntry::make('access_type')
                    ->label('Akses Video'),
                TextEntry::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
                IconEntry::make('is_published')
                    ->label('Publish')
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
