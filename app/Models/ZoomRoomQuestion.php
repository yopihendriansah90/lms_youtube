<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoomRoomQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'zoom_room_id',
        'member_id',
        'subject',
        'question',
        'asked_at',
        'seen_at',
    ];

    protected function casts(): array
    {
        return [
            'asked_at' => 'datetime',
            'seen_at' => 'datetime',
        ];
    }

    public function isSeen(): bool
    {
        return filled($this->seen_at);
    }

    public function zoomRoom(): BelongsTo
    {
        return $this->belongsTo(ZoomRoom::class);
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }
}
