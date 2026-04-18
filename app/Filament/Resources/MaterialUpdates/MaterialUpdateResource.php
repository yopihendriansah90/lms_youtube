<?php

namespace App\Filament\Resources\MaterialUpdates;

use App\Filament\Resources\MaterialUpdates\Pages\CreateMaterialUpdate;
use App\Filament\Resources\MaterialUpdates\Pages\EditMaterialUpdate;
use App\Filament\Resources\MaterialUpdates\Pages\ListMaterialUpdates;
use App\Filament\Resources\MaterialUpdates\Pages\ViewMaterialUpdate;
use App\Filament\Resources\MaterialUpdates\Schemas\MaterialUpdateForm;
use App\Filament\Resources\MaterialUpdates\Schemas\MaterialUpdateInfolist;
use App\Filament\Resources\MaterialUpdates\Tables\MaterialUpdatesTable;
use App\Models\MaterialUpdate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MaterialUpdateResource extends Resource
{
    protected static ?string $model = MaterialUpdate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $navigationLabel = 'Update Materi';

    protected static ?string $modelLabel = 'Update Materi';

    protected static ?string $pluralModelLabel = 'Update Materi';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Belajar';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return MaterialUpdateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaterialUpdateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialUpdatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMaterialUpdates::route('/'),
            'create' => CreateMaterialUpdate::route('/create'),
            'view' => ViewMaterialUpdate::route('/{record}'),
            'edit' => EditMaterialUpdate::route('/{record}/edit'),
        ];
    }
}
