<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerekKendaraanResource\Pages;
use App\Filament\Resources\MerekKendaraanResource\RelationManagers;
use App\Models\MerekKendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MerekKendaraanResource extends Resource
{
    protected static ?string $model = MerekKendaraan::class;

    protected static ?string $pluralLabel = " Merek Kendaraan";
    protected static ?string $slug = "MerekKendaraan";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_merek_kendaraan')
                ->required()
                ->maxLength(10)
                ->label('Kode Merek Kendaraan'),
                Forms\Components\TextInput::make('merek_kendaraan')
                ->required()
                ->maxLength(255)
                ->label('Nama Merek'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_merek_kendaraan'),
                Tables\Columns\TextColumn::make('merek_kendaraan'),
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
            'index' => Pages\ListMerekKendaraans::route('/'),
            'create' => Pages\CreateMerekKendaraan::route('/create'),
            'edit' => Pages\EditMerekKendaraan::route('/{record}/edit'),
        ];
    }
}
