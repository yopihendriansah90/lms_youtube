<?php

namespace App\Filament\Pages;

use App\Support\PortalSettings;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class HomeAppearanceSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Setting Web';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.home-appearance-settings';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'home_badge' => PortalSettings::get('portal.home_badge'),
            'hero_title' => PortalSettings::get('portal.hero_title', 'Selamat Datang di Alfaruq WFA'),
            'hero_description' => PortalSettings::get('portal.hero_description', 'Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan Anda dalam satu ruang kerja digital yang terintegrasi.'),
            'hero_video_url' => PortalSettings::get('portal.hero_video_url', 'https://www.youtube.com/watch?v=M7lc1UVf-VE'),
            'hero_video_heading' => PortalSettings::get('portal.hero_video_heading', 'Video Penjelasan'),
            'hero_video_caption' => PortalSettings::get('portal.hero_video_caption', 'Tempat Video'),
            'active_meeting_status' => PortalSettings::get('portal.active_meeting_status', 'Sedang Berlangsung'),
            'active_meeting_title' => PortalSettings::get('portal.active_meeting_title', 'Workshop React Hooks - Batch 12'),
            'active_meeting_schedule' => PortalSettings::get('portal.active_meeting_schedule', 'Kamis, 23 Januari 2025'),
            'active_meeting_time' => PortalSettings::get('portal.active_meeting_time', '14:00 - 16:00 WIB'),
            'admin_whatsapp_number' => PortalSettings::get('portal.admin_whatsapp_number'),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Header Home')
                    ->description('Atur badge, judul utama, dan deskripsi pembuka di beranda member.')
                    ->schema([
                        TextInput::make('home_badge')
                            ->label('Badge')
                            ->placeholder('Dashboard Aktif')
                            ->helperText('Opsional. Jika kosong, badge tidak akan ditampilkan di home.')
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('hero_title')
                            ->label('Judul Home')
                            ->placeholder('Selamat Datang di Alfaruq WFA')
                            ->helperText('Judul utama besar pada header home.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        Textarea::make('hero_description')
                            ->label('Deskripsi Home')
                            ->placeholder('Akses materi pelatihan, sesi tanya jawab, dan rekaman pertemuan...')
                            ->helperText('Teks pembuka singkat di bawah judul home.')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->live(onBlur: true),
                    ])
                    ->columns(2),
                Section::make('Hero Video')
                    ->description('Atur video YouTube utama dan teks pendukung yang tampil di bawah video hero.')
                    ->schema([
                        TextInput::make('hero_video_url')
                            ->label('URL YouTube')
                            ->placeholder('https://www.youtube.com/watch?v=M7lc1UVf-VE')
                            ->helperText('Gunakan URL YouTube yang valid: youtube.com/watch, youtu.be, atau embed.')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('hero_video_heading')
                            ->label('Judul Video')
                            ->placeholder('Video Penjelasan')
                            ->helperText('Judul yang tampil tepat di bawah hero video.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('hero_video_caption')
                            ->label('Caption Video')
                            ->placeholder('Fondasi Konten YouTube')
                            ->helperText('Caption kecil di bawah judul video.')
                            ->maxLength(255)
                            ->live(onBlur: true),
                    ])
                    ->columns(2),
                Section::make('Room Zoom Aktif')
                    ->description('Atur informasi sesi meeting aktif yang ditampilkan di home member.')
                    ->schema([
                        TextInput::make('active_meeting_status')
                            ->label('Status')
                            ->placeholder('Sedang Berlangsung')
                            ->helperText('Label status meeting aktif.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('active_meeting_title')
                            ->label('Judul Meeting')
                            ->placeholder('Workshop React Hooks - Batch 12')
                            ->helperText('Judul utama pada blok room Zoom.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('active_meeting_schedule')
                            ->label('Tanggal')
                            ->placeholder('Kamis, 23 Januari 2025')
                            ->helperText('Format bebas sesuai kebutuhan tampilan.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                        TextInput::make('active_meeting_time')
                            ->label('Jam')
                            ->placeholder('14:00 - 16:00 WIB')
                            ->helperText('Jam sesi aktif yang tampil di home.')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),
                    ])
                    ->columns(2),
                Section::make('Kontak Admin')
                    ->description('Nomor WhatsApp ini dipakai tombol Minta Akses pada konten premium di halaman member.')
                    ->schema([
                        TextInput::make('admin_whatsapp_number')
                            ->label('Nomor WhatsApp Admin')
                            ->placeholder('081234567890 atau 6281234567890')
                            ->helperText('Gunakan nomor aktif admin. Sistem akan mengubah format 08 menjadi 62 secara otomatis.')
                            ->tel()
                            ->maxLength(30)
                            ->live(onBlur: true),
                    ])
                    ->columns(2),
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $this->validate([
            'data.hero_video_url' => ['required', 'url', 'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)[\w\-]{11}.*$/'],
        ], [
            'data.hero_video_url.regex' => 'URL video harus berupa link YouTube yang valid.',
        ]);

        PortalSettings::putMany([
            'portal.home_badge' => blank($data['home_badge']) ? null : $data['home_badge'],
            'portal.hero_title' => $data['hero_title'],
            'portal.hero_description' => $data['hero_description'],
            'portal.hero_video_url' => $data['hero_video_url'],
            'portal.hero_video_heading' => $data['hero_video_heading'],
            'portal.hero_video_caption' => $data['hero_video_caption'],
            'portal.active_meeting_status' => $data['active_meeting_status'],
            'portal.active_meeting_title' => $data['active_meeting_title'],
            'portal.active_meeting_schedule' => $data['active_meeting_schedule'],
            'portal.active_meeting_time' => $data['active_meeting_time'],
            'portal.admin_whatsapp_number' => blank($data['admin_whatsapp_number']) ? null : $data['admin_whatsapp_number'],
        ]);

        Notification::make()
            ->title('Pengaturan home berhasil disimpan.')
            ->success()
            ->send();
    }
}
