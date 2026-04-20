<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PremiumPayments\PremiumPaymentResource;
use App\Filament\Resources\ZoomRecords\ZoomRecordResource;
use App\Filament\Resources\ZoomRoomQuestions\ZoomRoomQuestionResource;
use App\Filament\Resources\ZoomRooms\ZoomRoomResource;
use App\Models\PremiumPayment;
use App\Models\ZoomRecord;
use App\Models\ZoomRoom;
use App\Models\ZoomRoomQuestion;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOperationsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Operasional';

    protected ?string $description = 'Pantau angka utama untuk live Zoom, pembayaran, dan arsip rekaman dari satu layar.';

    protected ?string $pollingInterval = '5s';

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $liveRooms = ZoomRoom::query()
            ->where('status', 'live')
            ->where('is_published', true)
            ->count();

        $liveQuestions = ZoomRoomQuestion::query()
            ->whereIn('zoom_room_id', ZoomRoom::query()
                ->where('status', 'live')
                ->where('is_published', true)
                ->pluck('id'))
            ->count();

        $pendingPayments = PremiumPayment::query()
            ->where('status', 'pending')
            ->count();

        $publishedRecords = ZoomRecord::query()
            ->where('is_published', true)
            ->count();

        return [
            Stat::make('Room Live', number_format($liveRooms))
                ->description($liveRooms > 0 ? 'Sesi Zoom sedang berlangsung saat ini' : 'Belum ada room live aktif')
                ->descriptionIcon(Heroicon::OutlinedVideoCamera)
                ->color($liveRooms > 0 ? 'success' : 'gray')
                ->url(ZoomRoomResource::getUrl('index')),
            Stat::make('Pertanyaan Live', number_format($liveQuestions))
                ->description('Pertanyaan yang masuk pada room live')
                ->descriptionIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->color('warning')
                ->url(ZoomRoomQuestionResource::getUrl('index')),
            Stat::make('Pembayaran Pending', number_format($pendingPayments))
                ->description($pendingPayments > 0 ? 'Menunggu verifikasi admin' : 'Tidak ada pembayaran yang tertunda')
                ->descriptionIcon(Heroicon::OutlinedCreditCard)
                ->color($pendingPayments > 0 ? 'danger' : 'success')
                ->url(PremiumPaymentResource::getUrl('index')),
            Stat::make('Rekaman Publish', number_format($publishedRecords))
                ->description('Arsip Zoom yang sudah siap dibuka member')
                ->descriptionIcon(Heroicon::OutlinedPlayCircle)
                ->color('info')
                ->url(ZoomRecordResource::getUrl('index')),
        ];
    }
}
