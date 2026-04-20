<div class="space-y-6">
    <div class="grid gap-4 md:grid-cols-2">
        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-white/50">Room Zoom</p>
            <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $question->zoomRoom?->title ?? '-' }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-white/55">
                {{ $question->zoomRoom?->mentor?->name ? 'Mentor: ' . $question->zoomRoom->mentor->name : 'Mentor belum ditetapkan' }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-white/50">Member</p>
            <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $question->member?->name ?? '-' }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-white/55">
                {{ $question->asked_at?->translatedFormat('d F Y, H:i') ?? '-' }}
            </p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-white/50">Subjek</p>
        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ $question->subject ?: 'Tanpa subjek' }}</p>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/10 dark:bg-white/5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-500 dark:text-white/50">Isi Pertanyaan</p>
        <div class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-white/85">{{ $question->question }}</div>
    </div>
</div>
