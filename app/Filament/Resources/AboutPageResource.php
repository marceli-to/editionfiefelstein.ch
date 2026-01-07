<?php
namespace App\Filament\Resources;
use App\Filament\Resources\AboutPageResource\Pages;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use App\Models\AboutPage;

class AboutPageResource extends Resource
{
  protected static ?string $model = AboutPage::class;

  protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-information-circle';

  protected static ?string $navigationLabel = 'Über uns';

  protected static ?string $modelLabel = 'Über uns';

  protected static ?string $pluralModelLabel = 'Über uns';

  protected static \UnitEnum|string|null $navigationGroup = 'Seiteninhalt';

  protected static ?int $navigationSort = 1;

  public static function form(Schema $form): Schema
  {
    return $form
      ->columns(2)
      ->schema([
          Section::make('Zitat')
            ->schema([
              Textarea::make('quote')
                ->label('Zitat')
                ->rows(4),
              TextInput::make('quote_author')
                ->label('Autor'),
            ])
            ->columnSpan(1),
          Section::make('Text')
            ->schema([
              RichEditor::make('text')
                ->label('Text')
                ->toolbarButtons([
                  'bold',
                  'orderedList',
                  'bulletList',
                  'h2',
                  'h3',
                  'link',
                  'redo',
                  'undo',
                ]),
            ])
            ->columnSpan(1),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->label('Last Updated'),
      ])
      ->filters([
      ])
      ->actions([
        EditAction::make(),
      ])
      ->bulkActions([
      ]);
  }

  public static function getRelations(): array
  {
    return [
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListAboutPages::route('/'),
      'create' => Pages\CreateAboutPage::route('/create'),
      'edit' => Pages\EditAboutPage::route('/{record}/edit'),
    ];
  }
}
