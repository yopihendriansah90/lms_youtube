<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\ZoomRoomQuestion;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendZoomRoomQuestionNotificationJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $zoomRoomQuestionId,
    ) {}

    public function handle(): void
    {
        $zoomRoomQuestion = ZoomRoomQuestion::query()
            ->with(['zoomRoom.mentor', 'member'])
            ->find($this->zoomRoomQuestionId);

        if (! $zoomRoomQuestion) {
            return;
        }

        $recipients = User::query()
            ->role(['super_admin', 'admin'])
            ->get();

        if ($zoomRoomQuestion->zoomRoom?->mentor) {
            $recipients->push($zoomRoomQuestion->zoomRoom->mentor);
        }

        $recipients = $recipients
            ->filter()
            ->unique('id')
            ->values();

        if ($recipients->isEmpty()) {
            return;
        }

        $body = collect([
            $zoomRoomQuestion->member?->name ? 'Member: ' . $zoomRoomQuestion->member->name : null,
            $zoomRoomQuestion->zoomRoom?->title ? 'Room: ' . $zoomRoomQuestion->zoomRoom->title : null,
            $zoomRoomQuestion->subject ? 'Subjek: ' . $zoomRoomQuestion->subject : null,
        ])->filter()->join(' • ');

        $notification = Notification::make()
            ->title('Pertanyaan Zoom baru masuk')
            ->body($body)
            ->warning()
            ->persistent();

        foreach ($recipients as $recipient) {
            $recipient->notifyNow($notification->toDatabase());
            DatabaseNotificationsSent::dispatch($recipient);
        }
    }
}
