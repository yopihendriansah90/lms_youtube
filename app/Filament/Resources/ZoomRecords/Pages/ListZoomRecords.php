<?php

namespace App\Filament\Resources\ZoomRecords\Pages;

use App\Filament\Resources\ZoomRecords\ZoomRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListZoomRecords extends ListRecords
{
    protected static string $resource = ZoomRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
