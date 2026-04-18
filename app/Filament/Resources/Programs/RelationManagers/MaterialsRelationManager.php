<?php

namespace App\Filament\Resources\Programs\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $title = 'Tema Materi Dalam Kelas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tema Materi Dalam Kelas')
                    ->schema([
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
                            ->label('Ringkasan')
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(5)
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail Materi')
                            ->image()
                            ->disk('public')
                            ->directory('materials/thumbnails')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
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
                            ->required(),
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->default(0)
                            ->prefix('Rp')
                            ->required(),
                        Toggle::make('is_featured')
                            ->label('Unggulan')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publish')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Materi')
                    ->searchable(),
                TextColumn::make('mentor.name')
                    ->label('Mentor')
                    ->placeholder('-'),
                TextColumn::make('access_type')
                    ->label('Akses')
                    ->badge(),
                TextColumn::make('price')
                    ->label('Harga')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
