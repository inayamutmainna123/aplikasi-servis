<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SparepartResource\Pages;
use App\Filament\Resources\SparepartResource\RelationManagers;
use App\Models\Sparepart;
use Filament\Actions\ImportAction as ActionsImportAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\FileUpload::make('gambar')
                            ->directory('sparepart')
                            ->columnSpan('full')
                            ->inlineLabel('1')
                            ->visibility('public')
                            ->label('Foto'),
                        Forms\Components\TextInput::make('nama_sparepart')
                            ->required()
                            ->label('Nama ')
                            ->columnSpan('full')
                            ->inlineLabel('1')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('harga_sparepart')
                            ->required()
                            ->prefix('Rp')
                            ->currencyMask(thousandSeparator: ',', decimalSeparator: '.', precision: 2)
                            ->numeric()
                            ->live(debounce: 700)
                            ->label('Harga')
                            ->columnSpan('full')
                            ->inlineLabel('3'),
                        Forms\Components\TextInput::make('stok_sparepart')
                            ->required()
                            ->numeric()
                            ->label('Stok')
                            ->columnSpan('full')
                            ->inlineLabel('3'),
                        Forms\Components\RichEditor::make('deskripsi')
                            ->required()
                            ->label('Deskripsi')
                            ->maxLength(255)
                            ->inlineLabel('3')
                            ->columnSpan('full'),

                    ])
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
                Tables\Columns\ImageColumn::make('gambar')
                    ->visibility('public')
                    ->label('Foto')
                    ->circular()
                    ->size(40),
                Tables\Columns\TextColumn::make('nama_sparepart')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('harga_sparepart')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('stok_sparepart')
                    ->label('Stok'),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Deskripsi')
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
                ])
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
