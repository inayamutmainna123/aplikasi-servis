<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MerekKendaraanResource\Pages;
use App\Models\MerekKendaraan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;

class MerekKendaraanResource extends Resource
{
    protected static ?string $model = MerekKendaraan::class;

    protected static ?string $pluralLabel = " Merek Kendaraan";

    protected static ?string $slug = "merek_kendaraan";

    protected static ?string $navigationGroup = "Data Master";

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('kode_merek_kendaraan')
                            ->required()
                            ->maxLength(10)
                            ->inlineLabel()
                            ->label('Kode'),
                        Forms\Components\TextInput::make('merek_kendaraan')
                            ->required()
                            ->inlineLabel()
                            ->maxLength(255)
                            ->label('Merek'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('kode_merek_kendaraan')
                    ->label('Kode')
                    ->alignCenter()
                    ->wrapHeader(),
                Tables\Columns\TextColumn::make('merek_kendaraan')
                    ->label('Merek')
                    ->alignCenter()
                    ->wrapHeader()
                    ->searchable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMerekKendaraans::route('/'),
        ];
    }
}
