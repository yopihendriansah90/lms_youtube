<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\HomeAppearanceSettings;
use App\Filament\Resources\PremiumPayments\PremiumPaymentResource;
use App\Filament\Resources\ZoomRecords\ZoomRecordResource;
use App\Filament\Resources\ZoomRoomQuestions\ZoomRoomQuestionResource;
use App\Filament\Resources\ZoomRooms\ZoomRoomResource;
use Filament\Widgets\Widget;

class AdminQuickActions extends Widget
{
    protected static ?int $sort = 2;

    protected string $view = 'filament.widgets.admin-quick-actions';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [
            'actions' => [
                [
                    'title' => 'Pantau Pertanyaan Live',
                    'description' => 'Masuk ke daftar pertanyaan Zoom saat sesi live berlangsung.',
                    'url' => ZoomRoomQuestionResource::getUrl('index'),
                ],
                [
                    'title' => 'Kelola Room Zoom',
                    'description' => 'Atur jadwal, status room, link Zoom, dan mentor pengampu.',
                    'url' => ZoomRoomResource::getUrl('index'),
                ],
                [
                    'title' => 'Verifikasi Pembayaran',
                    'description' => 'Periksa order premium yang masuk dan verifikasi bukti bayar.',
                    'url' => PremiumPaymentResource::getUrl('index'),
                ],
                [
                    'title' => 'Buka Rekaman Zoom',
                    'description' => 'Cek rekaman terbaru yang sudah di-publish untuk member.',
                    'url' => ZoomRecordResource::getUrl('index'),
                ],
                [
                    'title' => 'Atur Tampilan Beranda',
                    'description' => 'Perbarui video hero, teks sambutan, dan nomor WhatsApp admin.',
                    'url' => HomeAppearanceSettings::getUrl(),
                ],
            ],
        ];
    }
}
