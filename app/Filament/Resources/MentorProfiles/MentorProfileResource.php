<?php

namespace App\Filament\Resources\MentorProfiles;

use App\Filament\Resources\MentorProfiles\Pages\CreateMentorProfile;
use App\Filament\Resources\MentorProfiles\Pages\EditMentorProfile;
use App\Filament\Resources\MentorProfiles\Pages\ListMentorProfiles;
use App\Filament\Resources\MentorProfiles\Pages\ViewMentorProfile;
use App\Filament\Resources\MentorProfiles\Schemas\MentorProfileForm;
use App\Filament\Resources\MentorProfiles\Schemas\MentorProfileInfolist;
use App\Filament\Resources\MentorProfiles\Tables\MentorProfilesTable;
use App\Models\MentorProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MentorProfileResource extends Resource
{
    protected static ?string $model = MentorProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $navigationLabel = 'Data Mentor';

    protected static ?string $modelLabel = 'Mentor';

    protected static ?string $pluralModelLabel = 'Data Mentor';

    protected static string|\UnitEnum|null $navigationGroup = 'Master LMS';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return MentorProfileForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MentorProfileInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MentorProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMentorProfiles::route('/'),
            'create' => CreateMentorProfile::route('/create'),
            'view' => ViewMentorProfile::route('/{record}'),
            'edit' => EditMentorProfile::route('/{record}/edit'),
        ];
    }
}
