<?php

namespace App\Filament\Resources\ZoomRooms\Pages;

use App\Filament\Resources\ZoomRooms\ZoomRoomResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZoomRoom extends EditRecord
{
    protected static string $resource = ZoomRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
