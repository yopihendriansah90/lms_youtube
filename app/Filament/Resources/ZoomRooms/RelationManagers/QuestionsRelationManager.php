<?php

namespace App\Filament\Resources\ZoomRooms\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Pertanyaan Member';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('member.name')
                    ->label('Member')
                    ->searchable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->placeholder('-')
                    ->wrap(),
                TextColumn::make('question')
                    ->label('Pertanyaan')
                    ->limit(90)
                    ->wrap(),
                TextColumn::make('asked_at')
                    ->label('Waktu Tanya')
                    ->dateTime('d M Y H:i')
                    ->description(fn ($record): ?string => $record->asked_at?->diffForHumans())
                    ->placeholder('-')
                    ->sortable(),
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
