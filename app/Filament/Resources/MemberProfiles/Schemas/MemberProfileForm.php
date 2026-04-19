<?php

namespace App\Filament\Resources\MemberProfiles\Schemas;

use App\Models\MemberProfile;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MemberProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Tambah Member')
                    ->description('Buat akun login member dengan cepat. Detail profil lainnya bisa dilengkapi pada update berikutnya.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Member')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email Login')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(
                                table: User::class,
                                column: 'email',
                                ignorable: fn (?MemberProfile $record): ?User => $record?->user,
                            )
                            ->validationMessages([
                                'unique' => 'Email ini sudah dipakai akun lain.',
                            ]),
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255)
                            ->helperText('Gunakan password minimal 8 karakter, atau klik tombol generate agar sistem membuat password yang aman.')
                            ->suffixAction(
                                Action::make('generatePassword')
                                    ->icon('heroicon-o-sparkles')
                                    ->tooltip('Generate password')
                                    ->action(function (Set $set): void {
                                        $set('password', static::generatePassword());
                                    }),
                                isInline: true,
                            )
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ])
                    ->columnSpanFull()
                    ->columns(1),
            ]);
    }

    protected static function generatePassword(): string
    {
        return Str::password(10, letters: true, numbers: true, symbols: false, spaces: false);
    }
}
