<?php

namespace App\Filament\Resources\ZoomRecords\Tables;

use App\Filament\Resources\ZoomRecords\ZoomRecordResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ZoomRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->label('Rekaman Zoom')
                    ->searchable()
                    ->description(fn ($record): string => collect([
                        $record->program?->title,
                        $record->mentor?->name,
                    ])->filter()->join(' • '))
                    ->wrap(),
                TextColumn::make('youtube_video_id')
                    ->label('Video ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'paid' ? 'warning' : 'success'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('content_unlocks_count')
                    ->counts('contentUnlocks')
                    ->label('Akses Member')
                    ->badge()
                    ->color('info'),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('recorded_at')
                    ->label('Tanggal Rekaman')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('recorded_at', 'desc')
            ->recordUrl(fn ($record) => ZoomRecordResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('access_type')
                    ->label('Tipe Akses')
                    ->options([
                        'free' => 'Gratis',
                        'paid' => 'Berbayar',
                    ]),
                TernaryFilter::make('is_published')
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
