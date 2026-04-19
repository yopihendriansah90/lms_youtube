<?php

namespace App\Filament\Resources\MemberProfiles\Pages;

use App\Filament\Resources\MemberProfiles\MemberProfileResource;
use App\Models\MemberProfile;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditMemberProfile extends EditRecord
{
    protected static string $resource = MemberProfileResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return 'Edit Member';
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->action(function (): void {
                    $user = $this->getRecord()->user;

                    if ($user) {
                        $user->delete();

                        return;
                    }

                    $this->getRecord()->delete();
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->getRecord()->user;

        return [
            'name' => $user?->name,
            'email' => $user?->email,
            'password' => null,
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var MemberProfile $record */
        DB::transaction(function () use ($data, $record): void {
            $record->user?->update([
                'name' => $data['name'],
                'email' => $data['email'],
                ...(
                    filled($data['password'] ?? null)
                        ? ['password' => $data['password']]
                        : []
                ),
            ]);
        });

        return $record->fresh(['user']);
    }
}
