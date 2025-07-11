<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparepartResource\Pages;
use App\Filament\Resources\SparepartResource\RelationManagers;
use App\Models\Sparepart;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\ImageUpload;
use Filament\Tables\Actions\ActionGroup;



class SparepartResource extends Resource
{
    protected static ?string $model = Sparepart::class;

    protected static ?string $pluralLabel = " Sparepart";

    protected static ?string $slug = "sparepart";

    protected static ?string $navigationGroup = "Data Master";

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('gambar')
                    ->directory('sparepart')
                    ->visibility('public')
                    ->label('Foto'),
                Forms\Components\TextInput::make('nama_sparepart')
                    ->required()
                    ->label('Nama Sparepart')
                    ->maxLength(255),
                Forms\Components\TextInput::make('harga_sparepart')
                    ->required()
                    ->numeric()
                    ->label('Harga Sparepart'),
                Forms\Components\TextInput::make('stok_sparepart')
                    ->required()
                    ->numeric()
                    ->label('Stok Sparepart'),
                Forms\Components\TextInput::make('deskripsi')
                    ->required()
                    ->label('Deskripsi')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar')
                    ->visibility('public')
                    ->label('Foto')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('nama_sparepart')
                    ->label('Nama Sparepart'),
                Tables\Columns\TextColumn::make('harga_sparepart')
                    ->label('Harga Sparepart'),
                Tables\Columns\TextColumn::make('stok_sparepart')
                    ->label('Stok Sparepart'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi'),
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
            'index' => Pages\ListSpareparts::route('/'),
            'create' => Pages\CreateSparepart::route('/create'),
            'edit' => Pages\EditSparepart::route('/{record}/edit'),
            
            
        ];
    }
}
