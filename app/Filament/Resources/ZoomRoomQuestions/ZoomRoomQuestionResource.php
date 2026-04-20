<?php

namespace App\Filament\Resources\ZoomRoomQuestions;

use App\Filament\Resources\ZoomRoomQuestions\Pages\ListZoomRoomQuestions;
use App\Filament\Resources\ZoomRoomQuestions\Tables\ZoomRoomQuestionsTable;
use App\Models\ZoomRoomQuestion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ZoomRoomQuestionResource extends Resource
{
    protected static ?string $model = ZoomRoomQuestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static ?string $navigationLabel = 'Pertanyaan Zoom';

    protected static ?string $modelLabel = 'Pertanyaan Zoom';

    protected static ?string $pluralModelLabel = 'Pertanyaan Zoom';

    protected static string|\UnitEnum|null $navigationGroup = 'Live Zoom';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return ZoomRoomQuestionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListZoomRoomQuestions::route('/'),
        ];
    }
}
