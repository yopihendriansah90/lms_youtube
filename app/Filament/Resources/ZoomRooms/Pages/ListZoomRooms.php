<?php

namespace App\Filament\Resources\ZoomRooms\Pages;

use App\Filament\Resources\ZoomRooms\ZoomRoomResource;
use App\Models\ZoomRoom;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

class ListZoomRooms extends ListRecords
{
    protected static string $resource = ZoomRoomResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(ZoomRoom::query()->count()),
            'live' => Tab::make('Sedang Berlangsung')
                ->badge(ZoomRoom::query()->where('status', 'live')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'live')),
            'scheduled' => Tab::make('Terjadwal')
                ->badge(ZoomRoom::query()->where('status', 'scheduled')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'scheduled')),
            'finished' => Tab::make('Riwayat')
                ->badge(ZoomRoom::query()->where('status', 'finished')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'finished')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->action(fn () => null),
            CreateAction::make()
                ->label('Tambah Room Zoom'),
        ];
    }
}
