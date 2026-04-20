<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin', 'mentor', 'panel_user']);
    }

    public function memberProfile(): HasOne
    {
        return $this->hasOne(MemberProfile::class);
    }

    public function mentorProfile(): HasOne
    {
        return $this->hasOne(MentorProfile::class);
    }

    public function mentoredMaterials(): HasMany
    {
        return $this->hasMany(Material::class, 'mentor_id');
    }

    public function mentoredZoomRecords(): HasMany
    {
        return $this->hasMany(ZoomRecord::class, 'mentor_id');
    }

    public function askedQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'member_id');
    }

    public function assignedQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'mentor_id');
    }

    public function questionAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class, 'mentor_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function contentUnlocks(): HasMany
    {
        return $this->hasMany(ContentUnlock::class);
    }

    public function premiumPayments(): HasMany
    {
        return $this->hasMany(PremiumPayment::class);
    }

    public function verifiedPremiumPayments(): HasMany
    {
        return $this->hasMany(PremiumPayment::class, 'verified_by');
    }

    public function materialPayments(): HasMany
    {
        return $this->premiumPayments();
    }

    public function verifiedMaterialPayments(): HasMany
    {
        return $this->verifiedPremiumPayments();
    }
}
