<x-member-layout title="Masuk Member LMS">
    <section class="mx-auto flex min-h-[calc(100vh-8rem)] max-w-xl items-center justify-center">
        <div class="glass-panel w-full overflow-hidden p-3">
            <div class="surface-card p-6 sm:p-8">
                <div class="mb-8 space-y-4 text-center">
                    <span class="section-pill">Akses Member</span>
                    <h1 class="display-heading mx-auto max-w-[11ch] text-[2.5rem] sm:text-[3rem]">Program Kelas Alfaruq WFA</h1>
                    <p class="mx-auto max-w-md text-[15px] leading-7 text-white/60">
                        Masuk untuk membuka materi, rekaman Zoom, dan update pembelajaran langsung dari portal kelas Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-2">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="field-shell" placeholder="Masukkan email yang digunakan saat pendaftaran" required autofocus>
                        @error('email')
                            <p class="text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="form-label">Password Rahasia</label>
                        <input id="password" name="password" type="password" class="field-shell" placeholder="Masukkan password rahasia Anda" required>
                        @error('password')
                            <p class="text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-white/60">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-white/20 bg-transparent text-brand-500">
                        Ingat akun saya di perangkat ini
                    </label>

                    <button type="submit" class="primary-btn w-full text-base">Masuk</button>
                </form>

                <div class="mt-8 grid gap-3 text-xs text-white/45 sm:grid-cols-3">
                    <div class="info-item">Materi terstruktur</div>
                    <div class="info-item">Rekaman Zoom terarsip</div>
                    <div class="info-item">Tanya jawab mentor</div>
                </div>
            </div>
        </div>
    </section>
</x-member-layout>
