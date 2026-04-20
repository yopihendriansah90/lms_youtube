<?php

namespace App\Filament\Resources\Videos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\Filament\Resources\Videos\VideoResource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class VideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('material.program.title')
                    ->label('Kelas Materi')
                    ->searchable(),
                TextColumn::make('material.title')
                    ->label('Materi Kelas')
                    ->searchable(),
                TextColumn::make('section.title')
                    ->label('Bagian')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Judul Video')
                    ->searchable(),
                TextColumn::make('youtube_video_id')
                    ->label('Video ID')
                    ->searchable(),
                TextColumn::make('duration_in_seconds')
                    ->label('Durasi')
                    ->numeric()
                    ->suffix(' detik')
                    ->sortable(),
                TextColumn::make('access_type')
                    ->label('Akses Video')
                    ->badge()
                    ->searchable(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordUrl(fn ($record) => VideoResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('material_id')
                    ->label('Materi Kelas')
                    ->relationship('material', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('access_type')
                    ->label('Tipe Akses')
                    ->options([
                        'free' => 'Gratis',
                        'paid' => 'Berbayar',
                    ]),
                \Filament\Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Publish'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
