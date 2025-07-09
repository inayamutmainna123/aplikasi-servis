<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\ServiceItem;
use App\Models\Sparepart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $pluralLabel = " Pembayaran";

    protected static ?string $slug = "Pembayaran";

    protected static ?int $navigationSort = 0;
    
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('costumer_id')
                ->relationship('costumer', 'nama_costumer')
                ->required()
                ->label('Customer'),

            DateTimePicker::make('tanggal_pembayaran')
                ->required()
                ->label('Tanggal Pembayaran'),

            Repeater::make('items')
                ->label('Detail Pembayaran')
                ->relationship('items')
                ->schema([
                    // SERVICE
            Select::make('service_item_id')
                ->label('Nama Service')
                ->relationship('ServiceItem', 'nama_service')
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                $harga_service = ServiceItem::find($state)?->harga_service ?? 0;
                $set('harga_service', $harga_service);
                    }),           
            TextInput::make('harga_service')
                ->numeric()
                ->readOnly()
                ->label('Harga Service'),
            TextInput::make('jumlah_service')
                ->numeric()
                ->default(0)
                ->reactive(),
            Select::make('sparepart_id')
                ->label('Nama Sparepart')
                ->relationship('Sparepart', 'nama_sparepart')
                ->options(fn () => Sparepart::pluck('nama_sparepart', 'id')) 
                ->searchable()
                ->preload()
                ->reactive()
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                $harga_sparepart = Sparepart::find($state)?->harga_sparepart ?? 0;
                $set('harga_sparepart', $harga_sparepart);
                    }),                
            TextInput::make('harga_sparepart')
                ->numeric()
                ->readOnly()
                ->label('Harga Sparepart'),
                

            TextInput::make('jumlah_sparepart')
                ->numeric()
                ->default(0)
                ->reactive(),
            ])
                ->columns(3)
                ->reactive()
                ->afterStateUpdated(function (Get $get, Set $set) {
                $items = $get('items');

                $total = collect($items)->sum(function ($item) {
                $totalService = (double)($item['harga_service'] ?? 0) * (double)($item['jumlah_service'] ?? 0);
                $totalSparepart = (double)($item['harga_sparepart'] ?? 0) * (double)($item['jumlah_sparepart'] ?? 0);
                return $totalService + $totalSparepart;
            });

                $set('total_harga', $total);
                $bayar = (double)($get('total_bayar') ?? 0);
                $set('total_kembali', $bayar - $total);
            }),

            TextInput::make('total_harga')
                ->numeric()
                ->readOnly()
                ->label('Total Harga'),
            TextInput::make('total_bayar')
                ->numeric()
                ->reactive()
                ->label('Total Bayar')
                ->afterStateUpdated(function ($state, Set $set, Get $get) {
                    $total = (double)($get('total_harga') ?? 0);
                    $set('total_kembali', (double)$state - $total);
                }),               
            TextInput::make('total_kembali')
                ->numeric()
                ->readOnly()
                ->label('Total Kembalian'),
            Select::make('status')
                ->options([
                    'lunas' => 'Lunas',
                    'belum lunas' => 'Belum Lunas',
                ])
                ->required(),
            Select::make('metode_pembayaran')
                ->options([
                    'cash' => 'Cash',
                ])
                ->default('cash')
                ->required(),
        ])      ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Costumer.nama_costumer')
                    ->label(' Nama Customer'),
                Tables\Columns\TextColumn::make('ServiceItem.nama_service')
                    ->label('Nama Service'),
                Tables\Columns\TextColumn::make('Sparepart.nama_sparepart')
                    ->label('Nama Sparepart'),
                Tables\Columns\TextColumn::make('ServiceItem.harga_service')
                    ->label('Harga Service'),
                Tables\Columns\TextColumn::make('Sparepart.harga_sparepart')
                    ->label('Harga Sparepart'),
                Tables\Columns\TextColumn::make('ServiceItem.jumlah_service')
                    ->label('Jumlah Service'),
                Tables\Columns\TextColumn::make('Sparepart.jumlah_sparepart')
                    ->label('Jumlah Sparepart'),
                Tables\Columns\TextColumn::make('total_harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total_bayar')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('total_kembali')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('tanggal_pembayaran')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(), 
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
