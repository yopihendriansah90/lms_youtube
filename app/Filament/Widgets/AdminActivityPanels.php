<?php

namespace App\Filament\Widgets;

use App\Models\PremiumPayment;
use App\Models\ZoomRoom;
use App\Models\ZoomRoomQuestion;
use Filament\Widgets\Widget;

class AdminActivityPanels extends Widget
{
    protected static ?int $sort = 3;

    protected string $view = 'filament.widgets.admin-activity-panels';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $liveRoomIds = ZoomRoom::query()
            ->where('status', 'live')
            ->where('is_published', true)
            ->pluck('id');

        return [
            'latestQuestions' => ZoomRoomQuestion::query()
                ->with(['member', 'zoomRoom'])
                ->when(
                    $liveRoomIds->isNotEmpty(),
                    fn ($query) => $query->whereIn('zoom_room_id', $liveRoomIds)
                )
                ->latest('asked_at')
                ->latest('id')
                ->limit(5)
                ->get(),
            'latestPayments' => PremiumPayment::query()
                ->with(['user', 'material', 'zoomRecord'])
                ->latest('paid_at')
                ->latest('id')
                ->limit(5)
                ->get(),
        ];
    }
}
