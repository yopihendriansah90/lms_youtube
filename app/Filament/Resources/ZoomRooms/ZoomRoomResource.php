<?php

namespace App\Filament\Resources\ZoomRooms;

use App\Filament\Resources\ZoomRooms\Pages\CreateZoomRoom;
use App\Filament\Resources\ZoomRooms\Pages\EditZoomRoom;
use App\Filament\Resources\ZoomRooms\Pages\ListZoomRooms;
use App\Filament\Resources\ZoomRooms\RelationManagers\QuestionsRelationManager;
use App\Filament\Resources\ZoomRooms\Schemas\ZoomRoomForm;
use App\Filament\Resources\ZoomRooms\Tables\ZoomRoomsTable;
use App\Models\ZoomRoom;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ZoomRoomResource extends Resource
{
    protected static ?string $model = ZoomRoom::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static ?string $navigationLabel = 'Room Zoom';

    protected static ?string $modelLabel = 'Room Zoom';

    protected static ?string $pluralModelLabel = 'Room Zoom';

    protected static string|\UnitEnum|null $navigationGroup = 'Live Zoom';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return ZoomRoomForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ZoomRoomsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $liveRoomsCount = ZoomRoom::query()
            ->where('status', 'live')
            ->where('is_published', true)
            ->count();

        return $liveRoomsCount > 0 ? (string) $liveRoomsCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZoomRooms::route('/'),
            'create' => CreateZoomRoom::route('/create'),
            'edit' => EditZoomRoom::route('/{record}/edit'),
        ];
    }
}
