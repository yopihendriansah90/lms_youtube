<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aksi Cepat Admin
        </x-slot>

        <x-slot name="description">
            Shortcut untuk membuka menu yang paling sering dipakai saat operasional harian.
        </x-slot>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="admin-dashboard-card"
                >
                    <div class="admin-dashboard-stack">
                        <span class="admin-dashboard-kicker">Shortcut</span>
                        <h3 class="admin-dashboard-card-title">{{ $action['title'] }}</h3>
                        <p class="admin-dashboard-card-copy">{{ $action['description'] }}</p>
                    </div>
                    <div class="mt-4">
                        <x-filament::button
                            tag="span"
                            size="sm"
                            color="primary"
                            icon="heroicon-m-arrow-top-right-on-square"
                        >
                            Buka Menu
                        </x-filament::button>
                    </div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
