<?php

namespace App\Filament\Resources\MaterialPayments\Pages;

use App\Filament\Resources\MaterialPayments\MaterialPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Validation\ValidationException;

class EditMaterialPayment extends EditRecord
{
    protected static string $resource = MaterialPaymentResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return 'Edit Pembayaran Premium';
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function onValidationError(ValidationException $exception): void
    {
        parent::onValidationError($exception);

        Notification::make()
            ->title('Form belum lengkap')
            ->body('Masih ada input wajib yang belum terisi. Silakan lengkapi field yang ditandai lalu simpan kembali.')
            ->danger()
            ->send();
    }
}
