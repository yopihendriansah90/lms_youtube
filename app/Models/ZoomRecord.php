<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

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
        'youtube_video_id',
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

    public function premiumPayments(): HasMany
    {
        return $this->hasMany(PremiumPayment::class);
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (filled($this->youtube_video_id)) {
            return "https://img.youtube.com/vi/{$this->youtube_video_id}/hqdefault.jpg";
        }

        if (! filled($this->thumbnail)) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        return Storage::disk('public')->url($this->thumbnail);
    }
}
