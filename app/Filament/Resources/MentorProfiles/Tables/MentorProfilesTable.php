<?php

namespace App\Filament\Resources\MentorProfiles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MentorProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan nomor urut berdasarkan pagination
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('user.name')
                    ->label('Akun User')
                    ->searchable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('display_name')
                    ->label('Nama Tampil')
                    ->searchable(),
                TextColumn::make('speciality')
                    ->label('Spesialisasi')
                    ->searchable(),
                ImageColumn::make('photo')
                    ->label('Foto')
                    ->circular(),
                TextColumn::make('instagram_url')
                    ->label('Instagram')
                    ->searchable(),
                TextColumn::make('youtube_url')
                    ->label('YouTube')
                    ->searchable(),
                TextColumn::make('whatsapp_number')
                    ->label('WhatsApp')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
                TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
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
