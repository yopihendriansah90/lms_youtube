<?php

namespace App\Filament\Resources\ZoomRoomQuestions\Pages;

use App\Filament\Resources\ZoomRoomQuestions\ZoomRoomQuestionResource;
use App\Models\ZoomRoomQuestion;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListZoomRoomQuestions extends ListRecords
{
    protected static string $resource = ZoomRoomQuestionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(ZoomRoomQuestion::query()->count()),
            'live' => Tab::make('Pertanyaan Live')
                ->badge(ZoomRoomQuestion::query()->whereHas('zoomRoom', fn (Builder $query) => $query->where('status', 'live'))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('zoomRoom', fn (Builder $zoomRoomQuery) => $zoomRoomQuery->where('status', 'live'))),
            'history' => Tab::make('Riwayat')
                ->badge(ZoomRoomQuestion::query()->whereHas('zoomRoom', fn (Builder $query) => $query->where('status', 'finished'))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('zoomRoom', fn (Builder $zoomRoomQuery) => $zoomRoomQuery->where('status', 'finished'))),
        ];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '3s';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->action(fn () => null),
        ];
    }
}
