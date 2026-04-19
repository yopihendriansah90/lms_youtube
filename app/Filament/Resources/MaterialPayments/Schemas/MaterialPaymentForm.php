<?php

namespace App\Filament\Resources\MaterialPayments\Schemas;

use App\Models\Material;
use App\Models\MaterialPayment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class MaterialPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Pembayaran Premium')
                    ->description('Catat pembayaran manual member untuk materi premium, lalu verifikasi agar akses premium aktif otomatis.')
                    ->schema([
                        Select::make('user_id')
                            ->label('Nama Member')
                            ->relationship(
                                name: 'user',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->role('member')
                                    ->orderBy('name')
                            )
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('material_id', null))
                            ->required()
                            ->helperText('Pilih akun member yang sudah mengirim pembayaran.'),
                        Select::make('material_id')
                            ->label('Nama Materi')
                            ->options(fn (Get $get, ?MaterialPayment $record): array => static::availableMaterialOptions(
                                memberId: $get('user_id'),
                                record: $record,
                            ))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->helperText('Hanya materi premium yang belum punya pembayaran verified untuk member ini yang akan tampil.'),
                        TextInput::make('amount')
                            ->label('Nominal Pembayaran')
                            ->prefix('Rp')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->helperText('Masukkan nominal transfer yang dibayarkan member.'),
                        FileUpload::make('payment_proof')
                            ->label('Upload Struk')
                            ->directory('material-payments/proofs')
                            ->acceptedFileTypes([
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                                'application/pdf',
                            ])
                            ->helperText('Bukti pembayaran bisa berupa gambar atau file PDF.')
                            ->columnSpanFull(),
                        Placeholder::make('payment_proof_actions')
                            ->label('Aksi Struk')
                            ->content(function (?MaterialPayment $record): HtmlString|string {
                                if (! $record?->payment_proof) {
                                    return 'Struk akan bisa dipreview atau didownload setelah pembayaran disimpan.';
                                }

                                $previewUrl = route('admin.material-payments.proof.preview', $record);
                                $downloadUrl = route('admin.material-payments.proof.download', $record);

                                return new HtmlString(
                                    '<div class="flex flex-wrap gap-3">'.
                                    '<a href="'.$previewUrl.'" target="_blank" rel="noopener noreferrer" class="fi-link fi-color-primary fi-size-sm">Preview Struk</a>'.
                                    '<a href="'.$downloadUrl.'" target="_blank" rel="noopener noreferrer" class="fi-link fi-color-gray fi-size-sm">Download Struk</a>'.
                                    '</div>'
                                );
                            })
                            ->columnSpanFull(),
                        DateTimePicker::make('paid_at')
                            ->label('Tanggal Pembayaran')
                            ->default(now())
                            ->seconds(false)
                            ->required(),
                        Select::make('status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'verified' => 'Verified',
                                'rejected' => 'Rejected',
                            ])
                            ->default('pending')
                            ->required()
                            ->disableOptionWhen(fn (string $value): bool => $value === 'verified' && ! static::canVerifyPayments())
                            ->helperText('Saat status diubah menjadi Verified, sistem otomatis membuka akses materi premium untuk member.'),
                        Textarea::make('notes')
                            ->label('Catatan Admin')
                            ->rows(4)
                            ->placeholder('Contoh: Diverifikasi dari bukti transfer via WhatsApp admin.')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }

    protected static function canVerifyPayments(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    protected static function availableMaterialOptions(?int $memberId, ?MaterialPayment $record = null): array
    {
        $materials = Material::query()
            ->where('access_type', 'paid')
            ->orderBy('title')
            ->get(['id', 'title']);

        if (blank($memberId)) {
            return $materials->pluck('title', 'id')->all();
        }

        $verifiedMaterialIds = MaterialPayment::query()
            ->where('user_id', $memberId)
            ->where('status', 'verified')
            ->when($record?->exists, fn (Builder $query) => $query->whereKeyNot($record->getKey()))
            ->pluck('material_id');

        return $materials
            ->reject(function (Material $material) use ($record, $verifiedMaterialIds): bool {
                if ($record?->material_id === $material->id) {
                    return false;
                }

                return $verifiedMaterialIds->contains($material->id);
            })
            ->pluck('title', 'id')
            ->all();
    }
}
