<?php

namespace App\Filament\Resources\MaterialUpdates\Pages;

use App\Filament\Resources\MaterialUpdates\MaterialUpdateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterialUpdate extends EditRecord
{
    protected static string $resource = MaterialUpdateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
