<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceItemResource\Pages;
use App\Filament\Resources\ServiceItemResource\RelationManagers;
use App\Models\ServiceItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PivotTable;
use Filament\Actions\ActionGroup as ActionsActionGroup;
use Filament\Actions\Modal\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\RichEditor;
use Filament\Support\RawJs;

class ServiceItemResource extends Resource
{
    protected static ?string $model = ServiceItem::class;

    protected static ?string $pluralLabel = " Service Item";

    protected static ?string $slug = "service_item";

    protected static ?string $navigationGroup = "Data Master";

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\TextInput::make('nama_service')
                            ->required()
                            ->label('Nama Service')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('harga_service')
                            ->required()
                            ->prefix('Rp')
                            // ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->label('Harga Service'),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->label('Deskripsi')
                            ->maxLength(255),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_service'),
                Tables\Columns\TextColumn::make('harga_service')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->html(),
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
            'index' => Pages\ManageServiceItems::route('/'),
        ];
    }
}
