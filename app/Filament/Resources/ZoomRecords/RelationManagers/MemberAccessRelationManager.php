<?php

namespace App\Filament\Resources\ZoomRecords\RelationManagers;

use App\Models\ZoomRecord;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;

class MemberAccessRelationManager extends RelationManager
{
    protected static string $relationship = 'contentUnlocks';

    protected static ?string $title = 'Akses Member';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akses Member Rekaman Zoom')
                    ->description('Tambahkan akun member yang diizinkan memutar rekaman Zoom ini.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pilih Member')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->role('member')
                                    ->orderBy('name')
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->rules([
                                fn () => Rule::unique('content_unlocks', 'user_id')
                                    ->where('unlockable_type', ZoomRecord::class)
                                    ->where('unlockable_id', $this->getOwnerRecord()->getKey())
                                    ->ignore($this->getMountedTableActionRecord()?->getKey()),
                            ])
                            ->validationMessages([
                                'unique' => 'Member ini sudah memiliki akses ke rekaman Zoom ini.',
                            ]),
                        Hidden::make('access_source')
                            ->default('manual')
                            ->dehydrated(true),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai Akses')
                            ->default(now())
                            ->seconds(false),
                        DateTimePicker::make('ends_at')
                            ->label('Berakhir Pada')
                            ->seconds(false),
                        Toggle::make('is_active')
                            ->label('Akses Aktif')
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('user.name')
                    ->label('Member')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('access_source')
                    ->label('Sumber')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state === 'manual' ? 'Manual' : ucfirst((string) $state)),
                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Member')
                    ->mutateDataUsing(fn (array $data): array => [
                        ...$data,
                        'access_source' => 'manual',
                        'is_active' => $data['is_active'] ?? true,
                    ])
                    ->modalHeading('Berikan Akses Rekaman Zoom')
                    ->modalDescription('Member yang ditambahkan di sini akan dapat memutar rekaman Zoom ini dari portal member.'),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateDataUsing(fn (array $data): array => [
                        ...$data,
                        'access_source' => 'manual',
                    ])
                    ->modalHeading('Ubah Akses Member'),
                DeleteAction::make()
                    ->label('Hapus Akses'),
            ]);
    }
}
