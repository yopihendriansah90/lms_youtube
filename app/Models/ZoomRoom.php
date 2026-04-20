<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ZoomRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'mentor_id',
        'title',
        'slug',
        'description',
        'join_url',
        'meeting_id',
        'passcode',
        'starts_at',
        'ends_at',
        'status',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ZoomRoomQuestion::class);
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }
}
