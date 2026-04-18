<?php

namespace App\Filament\Resources\MaterialUpdates\Pages;

use App\Filament\Resources\MaterialUpdates\MaterialUpdateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterialUpdate extends ViewRecord
{
    protected static string $resource = MaterialUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
