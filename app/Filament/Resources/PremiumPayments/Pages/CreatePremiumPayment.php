<?php

namespace App\Filament\Resources\PremiumPayments\Pages;

use App\Filament\Resources\PremiumPayments\PremiumPaymentResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Illuminate\Validation\ValidationException;

class CreatePremiumPayment extends CreateRecord
{
    protected static string $resource = PremiumPaymentResource::class;

    protected Width|string|null $maxContentWidth = Width::Full;

    public function getTitle(): string
    {
        return 'Tambah Pembayaran Premium';
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
