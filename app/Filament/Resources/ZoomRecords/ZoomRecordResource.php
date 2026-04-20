<?php

namespace App\Filament\Resources\ZoomRecords;

use App\Filament\Resources\ZoomRecords\Pages\CreateZoomRecord;
use App\Filament\Resources\ZoomRecords\Pages\EditZoomRecord;
use App\Filament\Resources\ZoomRecords\Pages\ListZoomRecords;
use App\Filament\Resources\ZoomRecords\RelationManagers\MemberAccessRelationManager;
use App\Filament\Resources\ZoomRecords\Schemas\ZoomRecordForm;
use App\Filament\Resources\ZoomRecords\Tables\ZoomRecordsTable;
use App\Models\ZoomRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ZoomRecordResource extends Resource
{
    protected static ?string $model = ZoomRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlayCircle;

    protected static ?string $navigationLabel = 'Rekaman Zoom';

    protected static ?string $modelLabel = 'Rekaman Zoom';

    protected static ?string $pluralModelLabel = 'Rekaman Zoom';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Belajar';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return ZoomRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZoomRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MemberAccessRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZoomRecords::route('/'),
            'create' => CreateZoomRecord::route('/create'),
            'edit' => EditZoomRecord::route('/{record}/edit'),
        ];
    }
}
