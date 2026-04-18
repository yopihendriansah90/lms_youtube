<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MentorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'speciality',
        'photo',
        'short_bio',
        'full_bio',
        'instagram_url',
        'youtube_url',
        'whatsapp_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (! filled($this->photo)) {
            return null;
        }

        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }

        if (Storage::disk('public')->exists($this->photo)) {
            return Storage::disk('public')->url($this->photo);
        }

        if (Storage::disk('local')->exists($this->photo)) {
            Storage::disk('public')->makeDirectory(dirname($this->photo));
            Storage::disk('public')->put($this->photo, Storage::disk('local')->get($this->photo));

            return Storage::disk('public')->url($this->photo);
        }

        return Storage::disk('public')->url($this->photo);
    }
}
