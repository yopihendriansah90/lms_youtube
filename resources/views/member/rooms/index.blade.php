<x-member-layout title="Room Zoom Meeting">
    <section class="page-stack">
        <div class="page-hero">
            <span class="section-pill">Live Zoom Room</span>
            <h1 class="page-title mt-4">Daftar Zoom Meeting</h1>
            <p class="page-hero-copy">
                Pantau sesi yang sedang berlangsung dan agenda meeting berikutnya dari satu halaman khusus.
            </p>
        </div>

        <div class="rich-card">
            <span class="status-chip is-live">Sedang Berlangsung</span>
            <h2 class="section-title mt-4">{{ $activeMeeting['title'] }}</h2>
            <p class="body-copy mt-2">{{ $activeMeeting['description'] }}</p>

            <div class="mt-5 grid gap-3 text-sm text-white/62">
                <div class="info-item">{{ $activeMeeting['schedule'] }}</div>
                <div class="info-item">{{ $activeMeeting['time'] }}</div>
                <div class="info-item">Meeting ID: {{ $activeMeeting['meeting_id'] }}</div>
                <div class="info-item">Password: {{ $activeMeeting['password'] }}</div>
            </div>

            <a href="{{ $activeMeeting['join_url'] }}" target="_blank" rel="noreferrer" class="primary-btn mt-5 w-full">Join Meeting Sekarang</a>
        </div>

        <div class="rich-card">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <p class="meta-copy">Jadwal Mendatang</p>
                    <h2 class="section-title mt-2">Meeting Berikutnya</h2>
                </div>
            </div>

            <div class="grid gap-3">
                @foreach ($upcomingMeetings as $meeting)
                    <div class="feature-list-card">
                        <div class="flex items-center justify-between gap-3">
                            <span class="eyebrow">{{ $meeting['day'] }}</span>
                            <span class="text-xs text-white/45">{{ $meeting['time'] }}</span>
                        </div>
                        <h3 class="card-heading mt-3">{{ $meeting['title'] }}</h3>
                        <p class="body-copy mt-2">{{ $meeting['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-member-layout>
