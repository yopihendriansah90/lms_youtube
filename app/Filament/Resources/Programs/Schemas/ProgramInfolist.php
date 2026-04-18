<?php

namespace App\Filament\Resources\Programs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProgramInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextEntry::make('title')
                    ->label('Nama Kelas'),
                TextEntry::make('slug')
                    ->label('Slug'),
                TextEntry::make('subtitle')
                    ->label('Subjudul Kelas')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label('Deskripsi')
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('cover_image')
                    ->label('Cover Kelas')
                    ->placeholder('-'),
                IconEntry::make('is_published')
                    ->label('Publish')
                    ->boolean(),
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
