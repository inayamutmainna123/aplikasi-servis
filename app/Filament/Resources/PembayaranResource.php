<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Models\Pembayaran;
use App\Models\ServiceDetail;
use App\Models\ServiceItem;
use App\Models\Sparepart;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\HtmlString;


class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $pluralLabel = " Pembayaran";

    protected static ?string $slug = "pembayaran";

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Data Pembayaran')->headerActions([
                    Forms\Components\Actions\Action::make('reset')
                        ->modalHeading('Are you sure?')
                        ->modalDescription('All existing items will be removed from the order.')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(fn(Forms\Set $set) => $set('items', [])),
                ])
                    ->schema([
                        Forms\Components\Select::make('costumer_id')
                            ->relationship('costumer', 'nama_costumer')
                            ->required()
                            ->native(false)
                            ->inlineLabel()
                            ->label('Customer'),
                        Forms\Components\DatePicker::make('tanggal_pembayaran')
                            ->required()
                            ->native(false)
                            ->inlineLabel()
                            ->label('Tanggal Pembayaran'),

                    ]),

                Forms\Components\Repeater::make('items')
                    ->label('Detail Pembayaran')
                    ->relationship('items')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('service_item_id')
                            ->label('Nama Service')
                            ->relationship('service_item', 'nama_service')
                            ->searchable()
                            ->inlineLabel()
                            ->preload()
                            ->columnSpan('full')
                            ->reactive()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $harga_service = ServiceItem::find($state)?->harga_service ?? 0;
                                $set('harga_service', $harga_service);
                            }),
                        Forms\Components\TextInput::make('harga_service')
                            ->prefix('Rp')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->numeric()
                            ->label('Harga Service')
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->default(fn(Get $get) => $get('harga_service')),
                        Forms\Components\TextInput::make('jumlah_service')
                            ->numeric()
                            ->inlineLabel()
                            ->columnSpan('full')
                            ->reactive(),
                        Forms\Components\Select::make('sparepart_id')
                            ->label('Nama Sparepart')
                            ->relationship('Sparepart', 'nama_sparepart')
                            ->options(fn() => Sparepart::pluck('nama_sparepart', 'id'))
                            ->searchable()
                            ->inlineLabel()
                            ->preload()
                            ->reactive()
                            ->columnSpan('full')
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $harga_sparepart = Sparepart::find($state)?->harga_sparepart ?? 0;
                                $set('harga_sparepart', $harga_sparepart);
                            }),
                        Forms\Components\TextInput::make('harga_sparepart')
                            ->prefix('Rp')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->numeric()
                            ->live(debounce: 700)
                            ->readOnly()
                            ->inlineLabel()
                            ->columnSpan('full')
                            ->label('Harga Sparepart')
                            ->default(fn(Get $get) => $get('harga_sparepart')),
                        Forms\Components\TextInput::make('jumlah_sparepart')
                            ->numeric()
                            ->default(0)
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->reactive()
                            ->rule(function (Get $get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $sparepart = sparepart::find($get('sparepart_id'));
                                    if ($sparepart && $value > $sparepart->stok_sparepart) {
                                        $fail("Jumlah melebihi stok tersedia ({$sparepart->stok_sparepart}).");
                                    }
                                };
                            })

                            ->helperText(function (Get $get) {
                                $sparepart = sparepart::find($get('sparepart_id'));
                                return $sparepart ? "Stok tersedia: {$sparepart->stok_sparepart}" : null;
                            }),
                    ])
                    ->columns(3)
                    ->reactive()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        $items = $get('items');

                        $total = collect($items)->sum(function ($item) {
                            $totalService = (float)($item['harga_service'] ?? 0) * (float)($item['jumlah_service'] ?? 0);
                            $totalSparepart = (float)($item['harga_sparepart'] ?? 0) * (float)($item['jumlah_sparepart'] ?? 0);
                            return $totalService + $totalSparepart;
                        });

                        $set('total_harga', $total);
                        $bayar = (float)($get('total_bayar') ?? 0);
                        $set('total_kembali', $bayar - $total);
                    }),

                Forms\Components\Section::make('Total Pembayaran')
                    ->columnSpan('full')
                    ->schema([
                        Forms\Components\TextInput::make('total_harga')
                            //->readOnly()
                            ->prefix('Rp')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->numeric()
                            ->live(debounce: 700)
                            ->label('Total Harga'),
                        Forms\Components\TextInput::make('total_bayar')
                            ->reactive()
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->label('Total Bayar')
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $total = (float)($get('total_harga') ?? 0);
                                $set('total_kembali', (float)$state - $total);
                            }),
                        Forms\Components\TextInput::make('total_kembali')
                            //->readOnly()
                            ->prefix('Rp')
                            ->numeric()
                            ->live(debounce: 700)
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->label('Total Kembalian'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'lunas' => 'Lunas',
                                'belum lunas' => 'Belum Lunas',
                            ])
                            ->default('belum lunas')
                            ->required(),
                        Forms\Components\Select::make('metode_pembayaran')
                            ->options([
                                'cash' => 'Cash',
                            ])
                            ->default('cash')
                            ->required(),
                    ])

                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->alignCenter()
                    ->wrapHeader()
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('costumer.nama_costumer')
                    ->label('Customer')
                    ->searchable()
                    ->visibleFrom('md')
                    ->formatStateUsing(fn($record) => new HtmlString(<<<HTML5
                        <div class="items-center">
                            <div class="font-bold">{$record->costumer->nama_costumer}</div>
                            <div class="text-xs">{$record->tanggal_pembayaran->format('M d Y')}</div>
                        </div>
                    HTML5)),

                Tables\Columns\TextColumn::make('items')
                    ->label('Service & Sparepart')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item) {
                            $service = optional($item->service_item)->nama_service;
                            $sparepart = optional($item->sparepart)->nama_sparepart;


                            return collect([$service, $sparepart])
                                ->filter()
                                ->implode(' + ');
                        })->filter();
                    })
                    ->bulleted()
                    ->alignCenter()
                    ->wrapHeader(),
                Tables\Columns\TextColumn::make('harga_detail')
                    ->label('Harga Detail')
                    ->html() // agar <br> bisa digunakan
                    ->getStateUsing(function ($record) {
                        $hargaServices = $record->items
                            ->map(fn($item) => optional($item->service_item)->harga_service)
                            ->filter()
                            ->map(fn($harga) => 'Rp ' . number_format($harga, 0, ',', '.'))
                            ->join(', ');

                        $hargaSpareparts = $record->items
                            ->map(fn($item) => optional($item->sparepart)->harga_sparepart)
                            ->filter()
                            ->map(fn($harga) => 'Rp ' . number_format($harga, 0, ',', '.'))
                            ->join(', ');

                        return "Service: $hargaServices<br>Sparepart: $hargaSpareparts";
                    })
                    ->alignCenter()
                    ->wrapHeader(),

                Tables\Columns\TextColumn::make('total_item')
                    ->label('Total Item')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        $jumlahSparepart = $record->items->sum('jumlah_sparepart');
                        $jumlahService = $record->items->sum('jumlah_service');
                        return $jumlahSparepart + $jumlahService;
                    })
                    ->formatStateUsing(fn($state) => number_format($state) . ' item'),


                Tables\Columns\TextColumn::make('total_harga')->money('IDR')
                    ->visibleFrom('md')
                    ->alignCenter()
                    ->wrapHeader()
                    ->label('Detail Total')
                    ->formatStateUsing(fn($record) => new HtmlString(<<<HTML5
                    <div class="items-center">
                        <div class="text-xs">Total Harga: {$record->total_harga}</div>
                        <div class="text-xs">Total Bayar: {$record->total_bayar}</div>
                        <div class="text-xs">Total Kembali: {$record->total_kembali}</div>
                    </div>
                HTML5)),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->visibleFrom('md')
                    ->alignCenter()
                    ->wrapHeader(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->visibleFrom('md')
                    ->alignCenter()
                    ->wrapHeader(),

            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum lunas' => 'Belum Lunas'
                    ]),

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('view_invoice')
                        ->label('View Invoice')
                        ->icon('heroicon-s-document-text')
                        // ->url(fn($record) => self::getUrl("invoice", ['record' => $record->id]))
                        ->url(fn($record) => route('pembayaran.cetak', $record), shouldOpenInNewTab: true)
                ])
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
            'invoice' => Pages\Invoice::route('/{record}/invoice'),
        ];
    }
}
