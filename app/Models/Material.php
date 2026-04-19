<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'mentor_id',
        'title',
        'slug',
        'excerpt',
        'description',
        'thumbnail',
        'status',
        'visibility',
        'access_type',
        'price',
        'currency',
        'is_featured',
        'published_at',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
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

    public function sections(): HasMany
    {
        return $this->hasMany(MaterialSection::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function pdfDocuments(): HasMany
    {
        return $this->hasMany(PdfDocument::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(MaterialUpdate::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        if (! filled($this->thumbnail)) {
            return null;
        }

        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }

        if (Storage::disk('public')->exists($this->thumbnail)) {
            return Storage::disk('public')->url($this->thumbnail);
        }

        if (Storage::disk('local')->exists($this->thumbnail)) {
            Storage::disk('public')->makeDirectory(dirname($this->thumbnail));
            Storage::disk('public')->put($this->thumbnail, Storage::disk('local')->get($this->thumbnail));

            return Storage::disk('public')->url($this->thumbnail);
        }

        return Storage::disk('public')->url($this->thumbnail);
    }
}
