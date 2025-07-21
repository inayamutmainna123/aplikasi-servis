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
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;




class CostumerResource extends Resource
{
    protected static ?string $model = Costumer::class;
    protected static ?string $pluralLabel = " Costumer";
    protected static ?string $slug = "costumer";

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->aside()
                    ->columnSpan('full')
                    ->schema([
                        Forms\Components\TextInput::make('nama_costumer')
                            ->required()
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->label('Nama')
                            ->columns(1),
                        Forms\Components\TextInput::make('email_costumer')
                            ->email()
                            ->required()
                            ->label('Email')
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->columns(1),
                        Forms\Components\TextInput::make('no_hp_costumer')
                            ->tel()
                            ->required()
                            ->label('No HP')
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->columns(1),
                        Forms\Components\TextArea::make('alamat_costumer')
                            ->required()
                            ->label('Alamat')
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->columns(1),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('nama_costumer')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_costumer')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('no_hp_costumer')
                    ->label('No HP'),
                Tables\Columns\TextColumn::make('alamat_costumer')
                    ->label('Alamat'),
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
            'index' => Pages\ManageCostumers::route('/'),
        ];
    }
}
