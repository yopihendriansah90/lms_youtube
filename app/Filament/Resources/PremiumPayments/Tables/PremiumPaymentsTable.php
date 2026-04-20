<?php

namespace App\Filament\Resources\PremiumPayments\Tables;

use App\Filament\Resources\PremiumPayments\PremiumPaymentResource;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PremiumPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('payment_target_type')
                    ->label('Jenis Order')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'zoom_record' ? 'Rekaman Zoom' : 'Video Materi')
                    ->color(fn (string $state): string => $state === 'zoom_record' ? 'info' : 'success'),
                TextColumn::make('target_title')
                    ->label('Konten Premium')
                    ->state(fn ($record): ?string => $record->targetTitle())
                    ->wrap(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->formatStateUsing(fn (int $state): string => 'Rp '.number_format($state, 0, ',', '.'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                IconColumn::make('payment_proof')
                    ->label('Struk')
                    ->boolean()
                    ->state(fn ($record): bool => filled($record->payment_proof)),
                TextColumn::make('paid_at')
                    ->label('Tanggal Bayar')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                TextColumn::make('verifier.name')
                    ->label('Diverifikasi Oleh')
                    ->placeholder('-')
                    ->toggleable(),
                TextColumn::make('verified_at')
                    ->label('Tanggal Verifikasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(fn ($record) => PremiumPaymentResource::getUrl('edit', ['record' => $record]))
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('payment_target_type')
                    ->label('Jenis Order')
                    ->options([
                        'material' => 'Video Materi',
                        'zoom_record' => 'Rekaman Zoom',
                    ]),
            ])
            ->recordActions([
                Action::make('previewProof')
                    ->label('Preview Struk')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn ($record): bool => filled($record->payment_proof))
                    ->url(fn ($record): string => route('admin.premium-payments.proof.preview', $record))
                    ->openUrlInNewTab(),
                Action::make('downloadProof')
                    ->label('Download Struk')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn ($record): bool => filled($record->payment_proof))
                    ->url(fn ($record): string => route('admin.premium-payments.proof.download', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
