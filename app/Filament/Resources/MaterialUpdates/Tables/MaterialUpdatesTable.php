<?php

namespace App\Filament\Resources\MaterialUpdates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MaterialUpdatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('material.title')
                    ->label('Materi')
                    ->searchable(),
                TextColumn::make('title')
                    ->label('Judul Update')
                    ->searchable(),
                TextColumn::make('update_type')
                    ->label('Tipe')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Publish')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime()
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
            ->filters([
                SelectFilter::make('update_type')
                    ->label('Tipe Update')
                    ->options([
                        'info' => 'Informasi',
                        'announcement' => 'Pengumuman',
                        'release' => 'Rilis Baru',
                        'assignment' => 'Tugas',
                    ]),
                TernaryFilter::make('is_published')
                    ->label('Status Publish'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
