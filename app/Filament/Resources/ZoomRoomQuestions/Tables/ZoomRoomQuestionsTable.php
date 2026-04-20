<?php

namespace App\Filament\Resources\ZoomRoomQuestions\Tables;

use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ZoomRoomQuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('zoomRoom.title')
                    ->label('Room Zoom')
                    ->searchable()
                    ->description(fn ($record): string => collect([
                        match ($record->zoomRoom?->status) {
                            'live' => 'Sedang Berlangsung',
                            'scheduled' => 'Terjadwal',
                            'finished' => 'Selesai',
                            default => null,
                        },
                        $record->zoomRoom?->mentor?->name,
                    ])->filter()->join(' • '))
                    ->wrap(),
                TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->placeholder('-')
                    ->wrap(),
                TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(120)
                    ->wrap(),
                TextColumn::make('asked_at')
                    ->label('Waktu Tanya')
                    ->dateTime('d M Y H:i')
                    ->description(fn ($record): ?string => $record->asked_at?->diffForHumans())
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->defaultSort('asked_at', 'desc')
            ->filters([
                SelectFilter::make('zoom_room_id')
                    ->label('Room Zoom')
                    ->relationship('zoomRoom', 'title')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('room_status')
                    ->label('Status Room')
                    ->options([
                        'live' => 'Sedang Berlangsung',
                        'scheduled' => 'Terjadwal',
                        'finished' => 'Selesai',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->whereHas('zoomRoom', fn (Builder $zoomRoomQuery) => $zoomRoomQuery->where('status', $data['value']));
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat')
                    ->modalHeading('Detail Pertanyaan Zoom')
                    ->modalWidth(Width::FourExtraLarge)
                    ->modalContent(fn ($record) => view('filament.zoom-room-questions.modal-content', [
                        'question' => $record->fresh(['zoomRoom.mentor', 'member']),
                    ])),
            ]);
    }
}
