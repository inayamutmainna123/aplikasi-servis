<?php

namespace App\Filament\Resources;

use App\Enums\StatusServiceDetail;
use App\Filament\Resources\ServiceDetailResource\Pages;
use App\Filament\Resources\ServiceDetailResource\RelationManagers;
use App\Models\ServiceDetail;
use App\Models\Sparepart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;




class ServiceDetailResource extends Resource
{
    protected static ?string $model = ServiceDetail::class;

    protected static ?string $pluralLabel = " Service Detail";

    protected static ?string $slug = "service_detail";

    protected static ?string $navigationIcon = 'heroicon-o-document-text';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Service')
                    ->headerActions([
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
                            ->label('Costumer')
                            ->native(false)
                            ->inlineLabel()
                            ->required(),
                        Forms\Components\Select::make('tipe_kendaraan')
                            ->options([
                                'matic' => 'Matic',
                                'manual' => 'Manual',
                            ])
                            ->default('matic')
                            ->label('Tipe Kendaraan')
                            ->native(false)
                            ->inlineLabel()
                            ->required(),
                        Forms\Components\Select::make('merek_kendaraan_id')
                            ->relationship('merek_kendaraan', 'merek_kendaraan')
                            ->label('Merek Kendaraan')
                            ->inlineLabel()
                            ->native(false)
                            ->required(),
                        Forms\Components\TextInput::make('model_kendaraan')
                            ->label('Model Kendaraan')
                            ->inlineLabel(),
                        Forms\Components\TextInput::make('plat_kendaraan')
                            ->label('Plat Kendaraan')
                            ->inlineLabel()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_service')
                            ->label('Tanggal Service')
                            ->native(false)
                            ->required()
                            ->inlineLabel(),
                        Forms\Components\TextArea::make('catatan')
                            ->label('Catatan')
                            ->inlineLabel(),
                    ]),
                Forms\Components\Repeater::make('items')
                    ->relationship('items')
                    ->label('Service dan Sparepart')
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Select::make('service_item_id')
                            ->relationship('service_item', 'nama_service')
                            ->label('Service')
                            ->inlineLabel()
                            ->searchable()
                            ->live()
                            ->native(false)
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('sparepart_id')
                            ->relationship('sparepart', 'nama_sparepart')
                            ->label('Sparepart')
                            ->searchable()
                            ->inlineLabel()
                            ->native(false)
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) return;

                                $sparepart = Sparepart::find($state);

                                if (!$sparepart || (float) $sparepart->stok_sparepart <= 0) {
                                    Notification::make()
                                        ->title('Stok Habis')
                                        ->body("Stok untuk sparepart \"{$sparepart?->nama_sparepart}\" sudah habis.")
                                        ->danger()
                                        ->persistent()
                                        ->send();

                                    $set('sparepart_id', null);
                                }
                            })
                            ->required()
                            ->columnSpanFull()
                            ->inlineLabel(),
                    ]),
                Forms\Components\Section::make('Status Service')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'belum diperbaiki' => 'Belum Diperbaiki',
                                'sedang diperbaiki' => 'Sedang Diperbaiki',
                                'selesai diperbaiki' => 'Selesai Diperbaiki',
                            ])
                    ])
                    ->default('belum diperbaiki')
                    ->label('Status')

                    ->inlineLabel()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->wrapHeader()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('costumer.nama_costumer')
                    ->label('Costumer')
                    ->searchable()
                    ->columnSpanFull()
                    ->alignCenter()
                    ->wrapHeader()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('items')
                    ->label('Service & Sparepart')
                    ->getStateUsing(function ($record) {
                        return $record->items->map(function ($item) {
                            $service = optional($item->service_item)->nama_service;
                            $sparepart = optional($item->sparepart)->nama_sparepart;

                            // Gabungkan keduanya jika ada, jika tidak ambil salah satu
                            return collect([$service, $sparepart])
                                ->filter()
                                ->implode(' + ');
                        })->filter(); // Buang nilai kosong/null
                    })
                    ->bulleted()
                    ->alignCenter()
                    ->wrapHeader(),

                Tables\Columns\TextColumn::make('kendaraan_info')
                    ->label('Info Kendaraan')
                    ->alignCenter()
                    ->wrapHeader()
                    ->getStateUsing(function ($record) {
                        return collect([
                            'Tipe: ' . $record->tipe_kendaraan,
                            'Merek: ' . optional($record->merek_kendaraan)->merek_kendaraan,
                            'Model: ' . $record->model_kendaraan,
                            'Plat: ' . $record->plat_kendaraan,
                        ])->filter()

                            ->toArray();
                    })
                    ->bulleted()
                    ->visibleFrom('md'),

                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan')
                    ->sortable(true)
                    ->alignCenter()
                    ->wrapHeader()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->alignCenter()
                    ->wrapHeader()
                    ->visibleFrom('md'),
                Tables\Columns\TextColumn::make('tanggal_service')
                    ->label('Tanggal Service')
                    ->alignCenter()
                    ->wrapHeader()
                    ->visibleFrom('md')
                    ->date(),

            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'belum diperbaiki' => 'Belum Diperbaiki',
                        'sedang diperbaiki' => 'Sedang Diperbaiki',
                        'selesai diperbaiki' => 'Selesai Diperbaiki',
                    ]),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceDetails::route('/'),
            'create' => Pages\CreateServiceDetail::route('/create'),
            'edit' => Pages\EditServiceDetail::route('/{record}/edit'),
        ];
    }
}
