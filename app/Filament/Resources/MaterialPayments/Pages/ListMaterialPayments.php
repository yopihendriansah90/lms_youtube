<?php

namespace App\Filament\Resources\MaterialPayments\Pages;

use App\Filament\Resources\MaterialPayments\MaterialPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterialPayments extends ListRecords
{
    protected static string $resource = MaterialPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Pembayaran'),
        ];
    }
}
