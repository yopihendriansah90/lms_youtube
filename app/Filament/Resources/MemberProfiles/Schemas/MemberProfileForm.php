<?php

namespace App\Filament\Resources\MemberProfiles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Member')
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->maxLength(30),
                        FileUpload::make('avatar')
                            ->label('Avatar')
                            ->image()
                            ->directory('members/avatars')
                            ->imageEditor(),
                        TextInput::make('occupation')
                            ->label('Pekerjaan')
                            ->maxLength(255),
                        TextInput::make('city')
                            ->label('Kota')
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label('Provinsi')
                            ->maxLength(255),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'male' => 'Laki-laki',
                                'female' => 'Perempuan',
                            ]),
                        DateTimePicker::make('joined_at')
                            ->label('Tanggal Bergabung')
                            ->seconds(false),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                        Textarea::make('bio')
                            ->label('Bio Singkat')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
