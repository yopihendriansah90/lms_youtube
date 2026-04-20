<?php

namespace App\Filament\Resources\PremiumPayments\Pages;

use App\Filament\Resources\PremiumPayments\PremiumPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPremiumPayments extends ListRecords
{
    protected static string $resource = PremiumPaymentResource::class;

    public function getTabs(): array
    {
        return [
            'material' => Tab::make('Video Materi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_target_type', 'material')),
            'zoom_record' => Tab::make('Rekaman Zoom')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_target_type', 'zoom_record')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Pembayaran'),
        ];
    }
}
