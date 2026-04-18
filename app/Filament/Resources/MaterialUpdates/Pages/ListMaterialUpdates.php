<?php

namespace App\Filament\Resources\MaterialUpdates\Pages;

use App\Filament\Resources\MaterialUpdates\MaterialUpdateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterialUpdates extends ListRecords
{
    protected static string $resource = MaterialUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
