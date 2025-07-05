<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\ServiceItem;
use App\Models\Sparepart;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('costumer_id')
                ->relationship('costumer', 'nama_costumer')
                ->label('Costumer')
                ->required(),

            Repeater::make('items')
                ->label('Detail Pembayaran')
                ->schema([
                    Select::make('service_item_id')
                        ->label('Service')
                        ->options(fn () => ServiceItem::pluck('nama_service', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $service = ServiceItem::find($state);
                            $set('harga_service', $service?->harga_service ?? 0);
                        })
                        ->required(),

                    TextInput::make('harga_service')
                        ->label('Harga Service')
                        ->disabled()
                        ->numeric()
                        ->default(0),

                    Select::make('sparepart_id')
                        ->label('Sparepart')
                        ->options(fn () => Sparepart::pluck('nama_sparepart', 'id'))
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $sparepart = Sparepart::find($state);
                            $set('harga_sparepart', $sparepart?->harga_sparepart ?? 0);
                        })
                        ->required(),

                    TextInput::make('harga_sparepart')
                        ->label('Harga Sparepart')
                        ->disabled()
                        ->numeric()
                        ->default(0),

                    TextInput::make('jumlah_service')
                        ->label('Jumlah Service')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->reactive(),

                    TextInput::make('jumlah_sparepart')
                        ->label('Jumlah Sparepart')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->reactive(),
                ])
                ->columns(3)
                ->minItems(1)
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // Hitung ulang total harga ketika item berubah
                    $total = collect($state)->sum(function ($item) {
                        $hargaService = $item['harga_service'] ?? 0;
                        $jumlahService = $item['jumlah_service'] ?? 0;
                        $hargaSparepart = $item['harga_sparepart'] ?? 0;
                        $jumlahSparepart = $item['jumlah_sparepart'] ?? 0;

                        return ($hargaService * $jumlahService) + ($hargaSparepart * $jumlahSparepart);
                    });

                    $set('total_harga', $total);

                    // Juga hitung ulang kembalian jika total_bayar sudah diisi
                    $totalBayar = $get('total_bayar') ?? 0;
                    $set('total_kembalian', $totalBayar - $total);
                }),

            TextInput::make('total_harga')
                ->label('Total Harga')
                ->disabled()
                ->numeric()
                ->default(0),

            TextInput::make('total_bayar')
                ->label('Total Bayar')
                ->numeric()
                ->reactive()
                ->required()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $totalHarga = $get('total_harga') ?? 0;
                    $set('total_kembalian', $state - $totalHarga);
                }),

            TextInput::make('total_kembalian')
                ->label('Kembalian')
                ->disabled()
                ->numeric()
                ->default(0),

            Select::make('metode_pembayaran')
                ->label('Metode Pembayaran')
                ->options([
                    'cash' => 'Cash',
                ])
                ->default('cash')
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'belum lunas' => 'Belum Lunas',
                    'lunas' => 'Lunas',
                ])
                ->default('belum lunas')
                ->required(),

            DatePicker::make('tanggal_pembayaran')
                ->label('Tanggal Pembayaran')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('costumer.nama_costumer')->label('Costumer'),

            Tables\Columns\TextColumn::make('items')
                ->label('Service')
                ->getStateUsing(fn ($record) =>
                    $record->items->pluck('serviceItem.nama_service')->join(', ')
                ),

            Tables\Columns\TextColumn::make('items')
                ->label('Sparepart')
                ->getStateUsing(fn ($record) =>
                    $record->items->pluck('sparepart.nama_sparepart')->join(', ')
                ),
            
            Tables\Columns\TextColumn::make('items')
                ->label('Jumlah Service')
                ->getStateUsing(fn ($record) =>
                    $record->items->pluck('jumlah_service')->join(', ')
                ),

            Tables\Columns\TextColumn::make('items')
                ->label('Jumlah Sparepart')
                ->getStateUsing(fn ($record) =>
                    $record->items->pluck('jumlah_sparepart')->join(', ')
                ),

            Tables\Columns\TextColumn::make('total_harga')->label('Total Harga'),
            Tables\Columns\TextColumn::make('total_bayar')->label('Bayar'),
            Tables\Columns\TextColumn::make('total_kembalian')->label('Kembalian'),
            Tables\Columns\TextColumn::make('status')->label('Status'),
            Tables\Columns\TextColumn::make('tanggal_pembayaran')->date()->label('Tanggal Bayar'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}
