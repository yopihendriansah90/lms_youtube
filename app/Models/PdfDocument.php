<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PdfDocument extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'material_id',
        'title',
        'description',
        'access_type',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (PdfDocument $document): void {
            $document->access_type = 'free';
        });
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function contentUnlocks(): MorphMany
    {
        return $this->morphMany(ContentUnlock::class, 'unlockable');
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'purchasable');
    }

    public function downloadFileName(): string
    {
        $baseName = Str::of($this->title)
            ->replaceMatches('/[\\\\\\/:*?"<>|]+/', ' ')
            ->squish()
            ->trim(' .')
            ->value();

        return filled($baseName) ? $baseName.'.pdf' : 'dokumen.pdf';
    }
}
