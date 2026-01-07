<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ContactPageResource\Pages;
use App\Filament\Resources\ContactPageResource\RelationManagers;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\ContactPage;

class ContactPageResource extends Resource
{
  protected static ?string $model = ContactPage::class;

  protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

  protected static ?string $navigationLabel = 'Kontakt';

  protected static ?string $modelLabel = 'Kontakt';

  protected static ?string $pluralModelLabel = 'Kontakt';

  protected static \UnitEnum|string|null $navigationGroup = 'Seiteninhalt';

  protected static ?int $navigationSort = 2;

  public static function form(Schema $form): Schema
  {
    return $form
      ->columns(2)
      ->schema([
          Section::make('Kontakt/Impressum')
            ->schema([
              RichEditor::make('imprint')
                ->label('Kontakt/Impressum')
                ->toolbarButtons([
                  'bold',
                  'orderedList',
                  'bulletList',
                  'h2',
                  'h3',
                  'link',
                  'redo',
                  'undo',
                  'underline',
                ]),
              RichEditor::make('privacy')
                ->label('Datenschutz')
                ->toolbarButtons([
                  'bold',
                  'orderedList',
                  'bulletList',
                  'h2',
                  'h3',
                  'link',
                  'redo',
                  'undo',
                ])
            ])
            ->columnSpan(1),
          Group::make([
            Section::make('Copyright')
              ->schema([
                RichEditor::make('copyright')
                  ->label('Copyright')
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
              ]),
            Section::make('AGB')
            ->schema([
              Textarea::make('toc_title')
                ->label('Titel')
                ->placeholder('Titel der AGB'),
              Repeater::make('toc_items')
                ->label('AGB Elemente')
                ->addActionLabel('AGB Element hinzufügen')
                ->collapsible()
                ->collapsed()
                ->itemLabel(fn (array $state): ?string => $state['number'] . ' ' . $state['title'] ?? null)
                ->schema([
                TextInput::make('number')
                  ->label('Nummer')
                  ->placeholder('Nummer'),
                TextInput::make('title')
                  ->label('Titel')
                  ->placeholder('Titel'),
                RichEditor::make('text')
                  ->label('Text')
                  ->toolbarButtons([
                    'bold',
                    'orderedList',
                    'bulletList',
                    'link',
                  ]),
                ]),
            ]),
          ])->columnSpan(1),
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
      'index' => Pages\ListContactPages::route('/'),
      'create' => Pages\CreateContactPage::route('/create'),
      'edit' => Pages\EditContactPage::route('/{record}/edit'),
    ];
  }
}
