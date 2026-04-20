<?php

namespace App\Filament\Resources\ZoomRooms\Tables;

use App\Filament\Resources\ZoomRooms\ZoomRoomResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ZoomRoomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->label('Room Zoom')
                    ->searchable()
                    ->description(fn ($record): string => collect([
                        $record->program?->title,
                        $record->mentor?->name,
                    ])->filter()->join(' • '))
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'live' => 'Sedang Berlangsung',
                        'scheduled' => 'Terjadwal',
                        default => 'Selesai',
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'live' => 'success',
                        'scheduled' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('questions_count')
                    ->counts('questions')
                    ->label('Pertanyaan')
                    ->badge()
                    ->color('info'),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->description(fn ($record): ?string => $record->starts_at?->diffForHumans())
                    ->placeholder('-')
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('starts_at', 'desc')
            ->recordUrl(fn ($record) => ZoomRoomResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Sesi')
                    ->options([
                        'live' => 'Sedang Berlangsung',
                        'scheduled' => 'Terjadwal',
                        'finished' => 'Selesai',
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
