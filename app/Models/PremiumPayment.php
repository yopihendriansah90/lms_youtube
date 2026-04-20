<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PremiumPayment extends Model
{
    use HasFactory;

    protected $table = 'material_payments';

    protected $fillable = [
        'user_id',
        'payment_target_type',
        'material_id',
        'zoom_record_id',
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
        static::saving(function (PremiumPayment $payment): void {
            if ($payment->status === 'verified') {
                $payment->guardVerifiedPayment();
                $payment->verified_at ??= now();
                $payment->verified_by ??= auth()->id();

                return;
            }

            $payment->verified_at = null;
            $payment->verified_by = null;
        });

        static::saved(function (PremiumPayment $payment): void {
            $payment->syncMaterialUnlock();
        });

        static::deleted(function (PremiumPayment $payment): void {
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

    public function zoomRecord(): BelongsTo
    {
        return $this->belongsTo(ZoomRecord::class);
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
        $targetTitle = Str::of($this->targetTitle() ?? 'konten-premium')
            ->replaceMatches('/[\\\\\\/:*?"<>|]+/', ' ')
            ->squish()
            ->trim(' .')
            ->value();

        $extension = pathinfo((string) $this->payment_proof, PATHINFO_EXTENSION) ?: 'pdf';

        return filled($targetTitle)
            ? "struk-{$targetTitle}.{$extension}"
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
                'unlockable_type' => $this->unlockableType(),
                'unlockable_id' => $this->unlockableId(),
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
            ->where('payment_target_type', $this->payment_target_type)
            ->when(
                $this->isZoomPayment(),
                fn ($query) => $query->where('zoom_record_id', $this->zoom_record_id),
                fn ($query) => $query->where('material_id', $this->material_id),
            )
            ->where('status', 'verified')
            ->when($excludingPaymentId, fn ($query) => $query->whereKeyNot($excludingPaymentId))
            ->exists();

        if ($hasOtherVerifiedPayment) {
            return;
        }

        ContentUnlock::query()
            ->where('user_id', $this->user_id)
            ->where('unlockable_type', $this->unlockableType())
            ->where('unlockable_id', $this->unlockableId())
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
            || $this->isDirty('payment_target_type')
            || $this->isDirty('material_id')
            || $this->isDirty('zoom_record_id');

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
            ->where('payment_target_type', $this->payment_target_type)
            ->when(
                $this->isZoomPayment(),
                fn ($query) => $query->where('zoom_record_id', $this->zoom_record_id),
                fn ($query) => $query->where('material_id', $this->material_id),
            )
            ->where('status', 'verified')
            ->when($this->exists, fn ($query) => $query->whereKeyNot($this->getKey()))
            ->exists();

        if ($duplicateVerifiedPaymentExists) {
            throw ValidationException::withMessages([
                'status' => 'Member ini sudah memiliki pembayaran terverifikasi untuk konten premium yang sama.',
            ]);
        }
    }

    public function isZoomPayment(): bool
    {
        return $this->payment_target_type === 'zoom_record';
    }

    public function targetTitle(): ?string
    {
        return $this->isZoomPayment()
            ? $this->zoomRecord?->title
            : $this->material?->title;
    }

    public function targetTypeLabel(): string
    {
        return $this->isZoomPayment() ? 'Rekaman Zoom' : 'Video Materi';
    }

    protected function unlockableType(): string
    {
        return $this->isZoomPayment() ? ZoomRecord::class : Material::class;
    }

    protected function unlockableId(): ?int
    {
        return $this->isZoomPayment() ? $this->zoom_record_id : $this->material_id;
    }
}
