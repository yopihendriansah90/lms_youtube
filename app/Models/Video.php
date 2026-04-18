<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'material_id',
        'section_id',
        'title',
        'youtube_url',
        'youtube_video_id',
        'description',
        'duration_in_seconds',
        'access_type',
        'price',
        'is_preview',
        'is_published',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_preview' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MaterialSection::class, 'section_id');
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
