<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end">
                <x-filament::button wire:click="save" size="lg">
                    Simpan Pengaturan Home
                </x-filament::button>
            </div>
        </div>

        <div class="space-y-4 xl:sticky xl:top-6 xl:self-start">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="mb-4">
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Preview Header</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-white/60">Pratinjau cepat untuk teks home member.</p>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-[#0b0f17]">
                    @if (filled(data_get($this->data, 'home_badge')))
                        <span class="inline-flex rounded-full border border-primary-500/20 bg-primary-500/8 px-3 py-1 text-[10px] font-semibold tracking-[0.18em] uppercase text-primary-500">
                            {{ data_get($this->data, 'home_badge') }}
                        </span>
                    @endif
                    <h3 class="mt-4 text-2xl font-bold leading-tight tracking-tight text-gray-950 dark:text-white">
                        {{ data_get($this->data, 'hero_title', 'Selamat Datang di Alfaruq WFA') }}
                    </h3>
                    <p class="mt-3 text-sm leading-6 text-gray-500 dark:text-white/60">
                        {{ data_get($this->data, 'hero_description', 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.') }}
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="mb-4">
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Preview Hero Video</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-white/60">Validasi visual sebelum Anda simpan perubahan.</p>
                </div>

                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-black dark:border-white/10">
                    @php
                        $previewVideoId = \App\Support\PortalSettings::youtubeVideoId(data_get($this->data, 'hero_video_url'));
                    @endphp

                    @if ($previewVideoId)
                        <div class="aspect-video">
                            <iframe
                                class="h-full w-full"
                                src="https://www.youtube.com/embed/{{ $previewVideoId }}"
                                title="Preview Video Hero"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                            ></iframe>
                        </div>
                    @else
                        <div class="flex aspect-video items-center justify-center px-6 text-center text-sm text-white/55">
                            Masukkan URL YouTube yang valid untuk melihat preview video di sini.
                        </div>
                    @endif
                </div>

                <div class="mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-white/10 dark:bg-[#0b0f17]">
                    <p class="text-lg font-semibold text-gray-950 dark:text-white">
                        {{ data_get($this->data, 'hero_video_heading', 'Video Penjelasan') }}
                    </p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-white/55">
                        {{ data_get($this->data, 'hero_video_caption', 'Tempat Video') }}
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
                <div class="mb-4">
                    <h2 class="text-base font-semibold text-gray-950 dark:text-white">Preview Room Zoom</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-white/60">Tampilan blok room meeting aktif di beranda member.</p>
                </div>

                <div class="rounded-2xl border border-danger-500/20 bg-danger-500/8 p-4">
                    <span class="inline-flex rounded-full border border-danger-500/25 bg-danger-500/10 px-3 py-1 text-[11px] font-semibold text-danger-600 dark:text-danger-400">
                        {{ data_get($this->data, 'active_meeting_status', 'Sedang Berlangsung') }}
                    </span>
                    <p class="mt-4 text-lg font-semibold text-gray-950 dark:text-white">
                        {{ data_get($this->data, 'active_meeting_title', 'Workshop React Hooks - Batch 12') }}
                    </p>
                    <p class="mt-2 text-sm text-gray-500 dark:text-white/60">
                        {{ data_get($this->data, 'active_meeting_schedule', 'Kamis, 23 Januari 2025') }} • {{ data_get($this->data, 'active_meeting_time', '14:00 - 16:00 WIB') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
