<?php

namespace App\Filament\Resources\MaterialPayments;

use App\Filament\Resources\MaterialPayments\Pages\CreateMaterialPayment;
use App\Filament\Resources\MaterialPayments\Pages\EditMaterialPayment;
use App\Filament\Resources\MaterialPayments\Pages\ListMaterialPayments;
use App\Filament\Resources\MaterialPayments\Schemas\MaterialPaymentForm;
use App\Filament\Resources\MaterialPayments\Tables\MaterialPaymentsTable;
use App\Models\MaterialPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialPaymentResource extends Resource
{
    protected static ?string $model = MaterialPayment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Pembayaran Premium';

    protected static ?string $modelLabel = 'Pembayaran Premium';

    protected static ?string $pluralModelLabel = 'Pembayaran Premium';

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MaterialPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialPaymentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterialPayments::route('/'),
            'create' => CreateMaterialPayment::route('/create'),
            'edit' => EditMaterialPayment::route('/{record}/edit'),
        ];
    }
}
