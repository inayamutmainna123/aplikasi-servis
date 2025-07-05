<?php

namespace App\Filament\Resources;

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
use Filament\Notifications\Notification; 


class ServiceDetailResource extends Resource
{
    protected static ?string $model = ServiceDetail::class;

    protected static ?string $pluralLabel = " Service Detail";
    protected static ?string $slug = "ServiceDetail";


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\Select::make('costumer_id') 
                ->relationship('costumer', 'nama_costumer')
                ->label('Costumer')
                ->required(),

            Forms\Components\Select::make('tipe_kendaraan')
                ->options([
                    'matic'=> 'Matic',
                    'manual'=> 'Manual',
                ])
                ->default('matic')
                ->label('Tipe Kendaraan')
                ->required(),
            
            Forms\Components\Select::make('merek_kendaraan')
                ->options([
                    'yamaha'=> 'Yamaha',
                    'honda'=> 'Honda',
                    'suzuki'=> 'Suzuki',
                    'kawasaki'=> 'Kawasaki',
                ])
                ->default('yamaha')
                ->label('Merek Kendaraan')
                ->required(),

            Forms\Components\TextInput::make('model_kendaraan')
                ->label('Model Kendaraan'),

            Forms\Components\TextInput::make('plat_kendaraan')
                ->label('Plat Kendaraan'),

                Repeater::make('items')
                ->relationship('items')
                ->label('Service dan Sparepart')
                ->schema([
                    Forms\Components\Select::make('service_item_id')
                        ->relationship('serviceItem', 'nama_service')
                        ->label('Nama Service')
                        ->required(),
            
                    Forms\Components\Select::make('sparepart_id')
                        ->label('Nama Sparepart')
                        ->options(function () {
                            return Sparepart::pluck('nama_sparepart', 'id');
                        })
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            if (!$state) return;
                        
                            $sparepart = Sparepart::find($state);
                        
                            if (!$sparepart || (double) $sparepart->stok_sparepart <= 0) {
                                Notification::make()
                                    ->title('Stok Habis')
                                    ->body("Stok untuk sparepart \"{$sparepart?->nama_sparepart}\" sudah habis.")
                                    ->danger()
                                    ->persistent()
                                    ->send();
                        
                                $set('sparepart_id', null);
                            }
                        })
                        
                        ->required(),
                ])
                ->minItems(1)
                ->columns(2)
                ->required(),
            Forms\Components\TextInput::make('catatan')
                ->label('Catatan'),
    
            Forms\Components\Select::make('Status')
                ->options([
                    'belum diperbaiki'=> 'belum diperbaiki',
                    'sedang diperbaiki'=> 'sedang diperbaiki',
                    'selesai diperbaiki'=> 'selesai diperbaiki',
                ])
                ->default('belum diperbaiki')
                ->label('Status')
                ->required(),

            Forms\Components\DatePicker::make('tanggal_service')
                ->required()
                ->label('Tanggal Service'),


        ]);
           

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('costumer.nama_costumer')
                    ->label('Costumer'),
                Tables\Columns\TextColumn::make('service_items')
                    ->label('Nama Service')
                    ->getStateUsing(fn ($record) => 
                        $record->items->pluck('serviceItem.nama_service')->filter()->join(', ')
                    ),
                
                Tables\Columns\TextColumn::make('spareparts')
                    ->label('Nama Sparepart')
                    ->getStateUsing(fn ($record) => 
                        $record->items->pluck('sparepart.nama_sparepart')->filter()->join(', ')
                    ),
                Tables\Columns\TextColumn::make('tipe_kendaraan')
                ->label('Tipe Kendaraan'),
                Tables\Columns\TextColumn::make('merek_kendaraan')
                ->label('Merek Kendaraan'),
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
                Tables\Actions\EditAction::make(),
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
