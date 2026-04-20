<?php

namespace App\Filament\Resources\PremiumPayments;

use App\Filament\Resources\PremiumPayments\Pages\CreatePremiumPayment;
use App\Filament\Resources\PremiumPayments\Pages\EditPremiumPayment;
use App\Filament\Resources\PremiumPayments\Pages\ListPremiumPayments;
use App\Filament\Resources\PremiumPayments\Schemas\PremiumPaymentForm;
use App\Filament\Resources\PremiumPayments\Tables\PremiumPaymentsTable;
use App\Models\PremiumPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PremiumPaymentResource extends Resource
{
    protected static ?string $model = PremiumPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Pembayaran Premium';

    protected static ?string $modelLabel = 'Pembayaran Premium';

    protected static ?string $pluralModelLabel = 'Pembayaran Premium';

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PremiumPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PremiumPaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPremiumPayments::route('/'),
            'create' => CreatePremiumPayment::route('/create'),
            'edit' => EditPremiumPayment::route('/{record}/edit'),
        ];
    }
}
