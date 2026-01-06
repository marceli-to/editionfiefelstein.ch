<?php
namespace App\Filament\Resources;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Enums\ProductState;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification; 

class ProductResource extends Resource
{
  protected static ?string $model = Product::class;
  
  protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-photo';

  protected static ?string $navigationLabel = 'Produkte';

  protected static ?string $modelLabel = 'Produkt';

  protected static ?string $pluralModelLabel = 'Produkte';

  public static function form(Schema $form): Schema
  {
    return $form->columns(2)->schema([
        Section::make('Produkt')
        ->schema([

          TextInput::make('title')
            ->label('Titel')
            ->required()
            ->columnSpan('full'),

          TextInput::make('isbn')
            ->label('ISBN')
            ->columnSpan('full'),

          Repeater::make('attributes')
            ->simple(
              TextInput::make('description')
              ->label('Beschreibung')
            )
            ->label('Attribute')
            ->addActionLabel('Attribut hinzufügen')
            ->columnSpan('full'),

          Select::make('state')
            ->label('Status')
            ->options(collect(ProductState::cases())->mapWithKeys(fn ($state) => [$state->value => $state->label()]))
            ->required(),

          TextInput::make('price')
            ->label('Preis')
            ->numeric()
            ->required()
            ->columnSpan('full'),

          TextInput::make('shipping')
            ->label('Verpackung und Versand')
            ->numeric()
            ->columnSpan('full'),

          TextInput::make('stock')
            ->label('Anzahl verfügbar')
            ->integer()
            ->required()
            ->columnSpan('full'),

          Toggle::make('publish')
            ->label('Publizieren')
            ->inline(false),

        ])->columnSpan(1),

        Section::make('Medien')
          ->collapsible()
          ->schema([

          FileUpload::make('image')
            ->disk('public')
            ->image()
            ->imageEditor()
            ->required()
            ->label('Hauptbild')
            ->helperText('Erlaubte Dateitypen: JPG, PNG')
            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file, $get): string {
              $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
              $name = $fileName . '-' . uniqid() . '.' . $file->extension();
              return (string) str($name);
            }),

          TextInput::make('image_caption')
            ->label('Bildunterschrift')
            ->maxLength(255),

            Repeater::make('rows')
              ->label('Zeilen')
              ->itemLabel(fn (array $state): ?string => match($state['layout'] ?? null) {
                  '2_landscape_1_portrait' => '2 Querformat + 1 Hochformat',
                  '1_portrait_2_landscape' => '1 Hochformat + 2 Querformat',
                  'text_2_landscape' => 'Text + 2 Querformat',
                  '2_landscape_text' => '2 Querformat + Text',
                  default => 'Zeile',
              })
              ->addActionLabel('Zeile hinzufügen')
              ->columnSpan('full')
              ->collapsible()
              ->collapsed()
              ->schema([
                Select::make('layout')
                  ->label('Layout')
                  ->options([
                      '2_landscape_1_portrait' => '2 Querformat | 1 Hochformat',
                      '1_portrait_2_landscape' => '1 Hochformat | 2 Querformat',
                      'text_2_landscape' => 'Text | 2 Querformat',
                      '2_landscape_text' => '2 Querformat | Text',
                  ])
                  ->required()
                  ->live()
                  ->columnSpan('full'),

                // Images for landscape positions (used in multiple layouts)
                FileUpload::make('landscape_1')
                  ->disk('public')
                  ->label('Querformat 1')
                  ->image()
                  ->imageEditor()
                  ->visible(fn ($get) => in_array($get('layout'), ['2_landscape_1_portrait', '1_portrait_2_landscape', 'text_2_landscape', '2_landscape_text']))
                  ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->extension();
                  }),

                FileUpload::make('landscape_2')
                  ->disk('public')
                  ->label('Querformat 2')
                  ->image()
                  ->imageEditor()
                  ->visible(fn ($get) => in_array($get('layout'), ['2_landscape_1_portrait', '1_portrait_2_landscape', 'text_2_landscape', '2_landscape_text']))
                  ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->extension();
                  }),

                FileUpload::make('portrait')
                  ->disk('public')
                  ->label('Hochformat')
                  ->image()
                  ->imageEditor()
                  ->visible(fn ($get) => in_array($get('layout'), ['2_landscape_1_portrait', '1_portrait_2_landscape']))
                  ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                    return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid() . '.' . $file->extension();
                  }),

                // Text field (for text layouts)
                RichEditor::make('text')
                  ->label('Text')
                  ->toolbarButtons(['bold', 'orderedList', 'bulletList', 'link'])
                  ->visible(fn ($get) => in_array($get('layout'), ['text_2_landscape', '2_landscape_text'])),
            ])
        ])->columnSpan(1),
    ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->striped()
      ->defaultSort('title', 'ASC')
      ->reorderable('sort')
      ->defaultSort('sort', 'ASC')
      ->columns([
        
        ImageColumn::make('image')
          ->disk('public')
          ->label('Bild')
          ->circular()
          ->height(50),

          TextColumn::make('title')
            ->label('Titel')
            ->searchable()
            ->sortable(),

          TextColumn::make('isbn')
            ->label('ISBN')
            ->searchable()
            ->sortable(),

          TextColumn::make('price')
            ->label('Preis')
            ->searchable()
            ->sortable(),

          TextColumn::make('stock')
            ->label('Verfügbar')
            ->searchable()
            ->sortable(),
      ])
      ->filters([
      ])
      ->actions([
        ActionGroup::make([
          EditAction::make(),
          DeleteAction::make(),
        ]),
      ])
      ->bulkActions([
        BulkAction::make('delete')
          ->requiresConfirmation()
          ->action(fn ($records) => $records->each->delete()),
      ]);
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListProducts::route('/'),
      'create' => Pages\CreateProduct::route('/create'),
      'edit' => Pages\EditProduct::route('/{record}/edit'),
    ];
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()
      ->withoutGlobalScopes([
        SoftDeletingScope::class,
      ]);
  }
}
