<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Materi')
                    ->description('Isi identitas utama materi yang akan tampil pada halaman member.')
                    ->schema([
                        Select::make('mentor_id')
                            ->label('Mentor')
                            ->relationship('mentor', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Mentor ini akan ditampilkan pada halaman detail materi.'),
                        TextInput::make('title')
                            ->label('Judul Materi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Strategi Konten YouTube untuk Pemula')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state)))
                            ->helperText('Judul utama yang dilihat member pada daftar materi dan halaman detail.'),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Otomatis dibuat dari judul, tetapi tetap bisa kamu ubah jika perlu.'),
                        TextInput::make('excerpt')
                            ->label('Deskripsi Singkat')
                            ->maxLength(500)
                            ->placeholder('Ringkasan singkat untuk card materi di halaman member.')
                            ->helperText('Teks singkat ini dipakai sebagai ringkasan materi pada daftar materi.')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi Lengkap')
                            ->rows(6)
                            ->placeholder('Jelaskan isi materi, manfaat yang didapat member, dan gambaran isi video atau PDF.')
                            ->helperText('Deskripsi ini tampil pada halaman detail materi.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Tampilan Materi')
                    ->description('Atur cover utama dan penanda visual materi.')
                    ->schema([
                        FileUpload::make('thumbnail')
                            ->label('Cover Materi')
                            ->image()
                            ->directory('materials/thumbnails')
                            ->imageEditor()
                            ->helperText('Gambar ini dipakai sebagai cover atau banner materi pada halaman member.')
                            ->columnSpanFull(),
                        Toggle::make('is_featured')
                            ->label('Tampilkan sebagai materi unggulan')
                            ->default(false)
                            ->helperText('Materi unggulan dapat diprioritaskan di area highlight halaman member.'),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Angka lebih kecil akan tampil lebih dulu.'),
                    ])
                    ->columns(2),
                Section::make('Publish & Akses')
                    ->description('Atur siapa yang bisa melihat materi dan kapan materi dipublikasikan.')
                    ->schema([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'review' => 'Review',
                                'published' => 'Published',
                                'archived' => 'Arsip',
                            ])
                            ->default('draft')
                            ->required()
                            ->helperText('Gunakan Draft saat materi masih disusun, lalu ubah ke Published jika siap tampil.'),
                        Select::make('visibility')
                            ->label('Visibilitas')
                            ->options([
                                'private' => 'Private',
                                'members' => 'Khusus Member',
                                'public' => 'Publik',
                            ])
                            ->default('private')
                            ->required()
                            ->helperText('Untuk LMS biasanya gunakan Khusus Member agar hanya user login yang bisa melihat materi.'),
                        Select::make('access_type')
                            ->label('Akses Materi Default')
                            ->options([
                                'free' => 'Gratis',
                                'paid' => 'Berbayar',
                            ])
                            ->default('free')
                            ->required()
                            ->live()
                            ->helperText('Pengaturan default ini bisa dilanjutkan lebih detail pada tiap video atau PDF.')
                            ->afterStateUpdated(function (Set $set, ?string $state): void {
                                if ($state === 'free') {
                                    $set('price', 0);
                                }
                            }),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required()
                            ->minValue(0)
                            ->disabled(fn (Get $get): bool => $get('access_type') !== 'paid')
                            ->helperText(fn (Get $get): string => $get('access_type') === 'paid'
                                ? 'Isi harga materi default. Tiap video atau PDF masih bisa diatur aksesnya masing-masing.'
                                : 'Untuk materi gratis, harga akan disimpan sebagai 0.'
                            )
                            ->dehydrated(),
                        TextInput::make('currency')
                            ->label('Mata Uang')
                            ->default('IDR')
                            ->required()
                            ->maxLength(10)
                            ->helperText('Gunakan IDR untuk rupiah.'),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false)
                            ->helperText('Kosongkan jika ingin atur nanti setelah konten lengkap.'),
                    ])
                    ->columns(2),
            ]);
    }
}
