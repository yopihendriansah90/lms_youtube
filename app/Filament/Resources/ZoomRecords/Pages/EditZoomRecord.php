<?php

namespace App\Filament\Resources\ZoomRecords\Pages;

use App\Filament\Resources\ZoomRecords\ZoomRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditZoomRecord extends EditRecord
{
    protected static string $resource = ZoomRecordResource::class;

    public function getSubheading(): ?string
    {
        return 'Lengkapi detail rekaman ini, lalu atur member yang diizinkan memutar video dari tab Akses Member.';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
