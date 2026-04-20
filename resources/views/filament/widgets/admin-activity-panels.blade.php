<x-filament-widgets::widget wire:poll.5s>
    <div class="grid gap-6 xl:grid-cols-2">
        <x-filament::section>
            <x-slot name="heading">
                Pertanyaan Live Terbaru
            </x-slot>

            <x-slot name="description">
                Pertanyaan member yang paling baru pada sesi Zoom aktif.
            </x-slot>

            <div class="space-y-3">
                @forelse ($latestQuestions as $question)
                    <div class="admin-dashboard-card">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="admin-dashboard-card-title truncate">
                                    {{ $question->subject ?: 'Pertanyaan tanpa subjek' }}
                                </p>
                                <p class="admin-dashboard-card-muted mt-1">
                                    {{ $question->member?->name ?? 'Member' }} • {{ $question->zoomRoom?->title ?? 'Room Zoom' }}
                                </p>
                            </div>
                            <span class="admin-dashboard-card-muted whitespace-nowrap">
                                {{ optional($question->asked_at)->diffForHumans() ?? '-' }}
                            </span>
                        </div>
                        <p class="admin-dashboard-card-copy mt-3 line-clamp-2">
                            {{ $question->question }}
                        </p>
                    </div>
                @empty
                    <div class="admin-dashboard-card admin-dashboard-card-copy border-dashed">
                        Belum ada pertanyaan baru pada room live saat ini.
                    </div>
                @endforelse
            </div>
        </x-filament::section>

        <x-filament::section>
            <x-slot name="heading">
                Pembayaran Premium Terbaru
            </x-slot>

            <x-slot name="description">
                Ringkasan order terakhir yang masuk ke sistem pembayaran premium.
            </x-slot>

            <div class="space-y-3">
                @forelse ($latestPayments as $payment)
                    <div class="admin-dashboard-card">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="admin-dashboard-card-title truncate">
                                    {{ $payment->user?->name ?? 'Member' }}
                                </p>
                                <p class="admin-dashboard-card-muted mt-1">
                                    {{ $payment->targetTypeLabel() }} • {{ $payment->targetTitle() ?? 'Konten premium' }}
                                </p>
                            </div>
                            <span class="admin-dashboard-badge {{ $payment->status === 'verified' ? 'admin-dashboard-badge--success' : 'admin-dashboard-badge--warning' }}">
                                {{ $payment->status === 'verified' ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between gap-4 text-sm">
                            <span class="admin-dashboard-card-copy font-medium">
                                Rp {{ number_format((int) $payment->amount, 0, ',', '.') }}
                            </span>
                            <span class="admin-dashboard-card-muted">
                                {{ optional($payment->paid_at)->diffForHumans() ?? '-' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="admin-dashboard-card admin-dashboard-card-copy border-dashed">
                        Belum ada data pembayaran premium terbaru.
                    </div>
                @endforelse
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>
