<?php

namespace App\Filament\Resources\Materials\Tables;

use App\Filament\Resources\Materials\MaterialResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('title')
                    ->label('Materi')
                    ->searchable()
                    ->description(fn ($record): string => collect([
                        $record->program?->title,
                        $record->mentor?->name,
                    ])->filter()->join(' • '))
                    ->wrap(),
                TextColumn::make('excerpt')
                    ->label('Ringkasan')
                    ->limit(40)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'review' => 'warning',
                        'archived' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => $state === 'paid' ? 'warning' : 'success'),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('videos_count')
                    ->counts('videos')
                    ->label('Video')
                    ->badge()
                    ->color('info'),
                TextColumn::make('pdf_documents_count')
                    ->counts('pdfDocuments')
                    ->label('PDF')
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('visibility')
                    ->label('Visibilitas')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('published_at')
                    ->label('Publish')
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
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn ($record) => MaterialResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Review',
                        'published' => 'Published',
                        'archived' => 'Arsip',
                    ]),
                SelectFilter::make('access_type')
                    ->label('Tipe Akses')
                    ->options([
                        'free' => 'Gratis',
                        'paid' => 'Berbayar',
                    ]),
                TernaryFilter::make('is_featured')
                    ->label('Materi Unggulan'),
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
