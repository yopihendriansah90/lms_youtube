<?php

namespace App\Filament\Resources\MaterialUpdates\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MaterialUpdateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Update Materi')
                    ->schema([
                        TextEntry::make('material.title')
                            ->label('Materi'),
                        TextEntry::make('title')
                            ->label('Judul'),
                        TextEntry::make('update_type')
                            ->label('Tipe Update')
                            ->badge(),
                        IconEntry::make('is_published')
                            ->label('Publish')
                            ->boolean(),
                        TextEntry::make('published_at')
                            ->label('Tanggal Publish')
                            ->dateTime(),
                        TextEntry::make('content')
                            ->label('Isi Update')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
