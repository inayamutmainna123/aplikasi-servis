<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CostumerResource\Pages;
use App\Filament\Resources\CostumerResource\RelationManagers;
use App\Models\Costumer;
use Filament\Forms;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CostumerResource extends Resource
{
    protected static ?string $model = Costumer::class;
    protected static ?string $pluralLabel = " Costumer";
    protected static ?string $slug = "Costumer";

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_costumer')
                    ->required()
                    ->label('Nama Costumer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email_costumer')
                    ->email()
                    ->required()
                    ->label('Email Costumer'),
                Forms\Components\TextInput::make('no_hp_costumer')
                    ->tel()
                    ->required()
                    ->label('No HP Costumer'),
                Forms\Components\TextInput::make('alamat_costumer') 
                    ->required()
                    ->label('Alamat Costumer'),   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_costumer'),
                Tables\Columns\TextColumn::make('email_costumer'),
                Tables\Columns\TextColumn::make('no_hp_costumer'),
                Tables\Columns\TextColumn::make('alamat_costumer'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                
               
                
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
            'index' => Pages\ListCostumers::route('/'),
            'create' => Pages\CreateCostumer::route('/create'),
            'edit' => Pages\EditCostumer::route('/{record}/edit'),
        ];
    }
}
