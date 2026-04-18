<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ZoomRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'mentor_id',
        'title',
        'slug',
        'description',
        'zoom_recording_url',
        'youtube_url',
        'thumbnail',
        'recorded_at',
        'access_type',
        'price',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'datetime',
            'price' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function contentUnlocks(): MorphMany
    {
        return $this->morphMany(ContentUnlock::class, 'unlockable');
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'purchasable');
    }
}
