<?php

namespace App\Filament\Resources\MentorProfiles\Pages;

use App\Filament\Resources\MentorProfiles\MentorProfileResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMentorProfile extends ViewRecord
{
    protected static string $resource = MentorProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
