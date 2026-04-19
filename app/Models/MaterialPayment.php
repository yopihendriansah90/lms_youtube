<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MaterialPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'material_id',
        'amount',
        'payment_proof',
        'status',
        'paid_at',
        'verified_at',
        'verified_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'paid_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (MaterialPayment $payment): void {
            if ($payment->status === 'verified') {
                $payment->guardVerifiedPayment();
                $payment->verified_at ??= now();
                $payment->verified_by ??= auth()->id();

                return;
            }

            $payment->verified_at = null;
            $payment->verified_by = null;
        });

        static::saved(function (MaterialPayment $payment): void {
            $payment->syncMaterialUnlock();
        });

        static::deleted(function (MaterialPayment $payment): void {
            $payment->disableMaterialUnlock();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function paymentProofUrl(): ?string
    {
        if (blank($this->payment_proof)) {
            return null;
        }

        return Storage::disk('public')->url($this->payment_proof);
    }

    public function paymentProofDownloadName(): string
    {
        $materialTitle = Str::of($this->material?->title ?? 'materi')
            ->replaceMatches('/[\\\\\\/:*?"<>|]+/', ' ')
            ->squish()
            ->trim(' .')
            ->value();

        $extension = pathinfo((string) $this->payment_proof, PATHINFO_EXTENSION) ?: 'pdf';

        return filled($materialTitle)
            ? "struk-{$materialTitle}.{$extension}"
            : "struk-pembayaran.{$extension}";
    }

    public function syncMaterialUnlock(): void
    {
        if ($this->status !== 'verified') {
            $this->disableMaterialUnlock($this->id);

            return;
        }

        ContentUnlock::query()->updateOrCreate(
            [
                'user_id' => $this->user_id,
                'unlockable_type' => Material::class,
                'unlockable_id' => $this->material_id,
                'access_source' => 'payment',
            ],
            [
                'order_id' => null,
                'starts_at' => $this->paid_at ?? $this->verified_at ?? now(),
                'ends_at' => null,
                'is_active' => true,
            ],
        );
    }

    public function disableMaterialUnlock(?int $excludingPaymentId = null): void
    {
        $hasOtherVerifiedPayment = static::query()
            ->where('user_id', $this->user_id)
            ->where('material_id', $this->material_id)
            ->where('status', 'verified')
            ->when($excludingPaymentId, fn ($query) => $query->whereKeyNot($excludingPaymentId))
            ->exists();

        if ($hasOtherVerifiedPayment) {
            return;
        }

        ContentUnlock::query()
            ->where('user_id', $this->user_id)
            ->where('unlockable_type', Material::class)
            ->where('unlockable_id', $this->material_id)
            ->where('access_source', 'payment')
            ->update([
                'is_active' => false,
                'ends_at' => now(),
            ]);
    }

    protected function guardVerifiedPayment(): void
    {
        $requiresVerificationGuard = (! $this->exists)
            || $this->isDirty('status')
            || $this->isDirty('user_id')
            || $this->isDirty('material_id');

        if (! $requiresVerificationGuard) {
            return;
        }

        if (! app()->runningInConsole() && ! auth()->user()?->hasAnyRole(['super_admin', 'admin'])) {
            throw ValidationException::withMessages([
                'status' => 'Hanya admin atau super admin yang bisa melakukan verifikasi pembayaran.',
            ]);
        }

        $duplicateVerifiedPaymentExists = static::query()
            ->where('user_id', $this->user_id)
            ->where('material_id', $this->material_id)
            ->where('status', 'verified')
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists();

        if ($duplicateVerifiedPaymentExists) {
            throw ValidationException::withMessages([
                'status' => 'Member ini sudah memiliki pembayaran terverifikasi untuk materi yang sama.',
            ]);
        }
    }
}
