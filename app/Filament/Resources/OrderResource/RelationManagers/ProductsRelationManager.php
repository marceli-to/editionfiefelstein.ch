<?php
namespace App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
  protected static string $relationship = 'products';

  protected function getTableHeading(): string
  {
    return 'Produkte';
  }

  public function form(Schema $form): Schema
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('title')
          ->required()
          ->maxLength(255),
        Forms\Components\TextInput::make('quantity')
          ->required()
          ->numeric(),
        Forms\Components\TextInput::make('price')
          ->required()
          ->numeric(),
      ]);
  }

  public function table(Table $table): Table
  {
    return $table
      ->striped()
      ->columns([
        Tables\Columns\TextColumn::make('pivot.title')->label('Titel'),
        Tables\Columns\TextColumn::make('pivot.price')->label('Preis'),
        Tables\Columns\TextColumn::make('pivot.quantity')->label('Anzahl'),
      ])
      ->filters([
        //
      ])
      ->headerActions([
        // Tables\Actions\CreateAction::make(),
      ])
      ->actions([
        // Tables\Actions\EditAction::make(),
        // Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
          // Tables\Actions\BulkActionGroup::make([
          //   Tables\Actions\DeleteBulkAction::make(),
          // ]),
      ]);
  }
}