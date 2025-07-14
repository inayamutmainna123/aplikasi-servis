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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use App\Models\PivotTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\RichEditor;



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
                            ->native(false)
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Select::make('sparepart_id')
                            ->relationship('sparepart', 'nama_sparepart')
                            ->label('Sparepart')
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
                Tables\Columns\TextColumn::make('costumer.nama_costumer')
                    ->label('Costumer'),
                Tables\Columns\TextColumn::make('items')
                    ->label('Nama Service')
                    ->getStateUsing(
                        fn($record) =>
                        $record->items
                            ->map(fn($item) => optional($item->service_item)->nama_service)
                            ->filter()
                            ->join(', ')
                    ),
                Tables\Columns\TextColumn::make('sparepart')
                    ->label('Nama Sparepart')
                    ->getStateUsing(
                        fn($record) =>
                        $record->items->pluck('sparepart.nama_sparepart')->filter()->join(', ')
                    ),
                Tables\Columns\TextColumn::make('tipe_kendaraan')
                    ->label('Tipe Kendaraan'),
                Tables\Columns\TextColumn::make('merek_kendaraan.merek_kendaraan')
                    ->label('Merek Kendaraan')
                    ->getStateUsing(fn($record) => optional($record->merek_kendaraan)->merek_kendaraan),
                Tables\Columns\TextColumn::make('model_kendaraan')
                    ->label('Model Kendaraan'),
                Tables\Columns\TextColumn::make('plat_kendaraan')
                    ->label('Plat Kendaraan'),
                Tables\Columns\TextColumn::make('catatan')
                    ->label('Catatan'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status'),
                Tables\Columns\TextColumn::make('tanggal_service')
                    ->label('Tanggal Service'),

            ])
            ->filters([
                //
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
