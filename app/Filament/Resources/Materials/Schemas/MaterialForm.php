<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Materi')
                    ->schema([
                        Select::make('program_id')
                            ->label('Program')
                            ->relationship('program', 'title')
                            ->searchable()
                            ->preload(),
                        Select::make('mentor_id')
                            ->label('Mentor')
                            ->relationship('mentor', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('title')
                            ->label('Judul Materi')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('excerpt')
                            ->label('Ringkasan Singkat')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi Lengkap')
                            ->rows(6)
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail')
                            ->image()
                            ->directory('materials/thumbnails')
                            ->imageEditor(),
                    ])
                    ->columns(2),
                Section::make('Pengaturan Akses')
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
                            ->required(),
                        Select::make('visibility')
                            ->label('Visibilitas')
                            ->options([
                                'private' => 'Private',
                                'members' => 'Khusus Member',
                                'public' => 'Publik',
                            ])
                            ->default('private')
                            ->required(),
                        Select::make('access_type')
                            ->label('Tipe Akses')
                            ->options([
                                'free' => 'Gratis',
                                'paid' => 'Berbayar',
                            ])
                            ->default('free')
                            ->required()
                            ->live(),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('currency')
                            ->label('Mata Uang')
                            ->default('IDR')
                            ->required()
                            ->maxLength(10),
                        Toggle::make('is_featured')
                            ->label('Materi Unggulan')
                            ->default(false)
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
