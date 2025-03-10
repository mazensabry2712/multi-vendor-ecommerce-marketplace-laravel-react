<?php

namespace App\Filament\Resources;

use Dom\Text;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Enums\RolesEnum;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use function Laravel\Prompts\search;
use Filament\Forms\Components\Select;
use App\Enums\Enums\ProductStatusEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
              Grid::make()
              ->schema([
                TextInput::make('title')
                ->live(onBlur: true)
                ->required()
                ->afterStateUpdated(
                    function(string $operation , $state , callable $set) {
                    $set("slug", Str::slug($state));
                    }
                ),
                TextInput::make('slug')
                ->required(),
                Select::make('department_id')
                ->relationship('department','name')
                ->label(__('Department'))
                ->preload()
                ->searchable()
                ->required()
                ->reactive() //make the field reactive TO CHANGES
                ->afterStateUpdated(
                    function(callable $set) {
                    $set("category_id", null);
                    }
                ),


                Select::make('category_id')
                 ->relationship(name: 'category',
                        titleAttribute: 'name',
                         modifyQueryUsing: function(Builder $query,callable $get) {
                          //Modyfy the category query based on the selected department
                         $departmentId = $get('department_id'); //get the selected department
                         if($departmentId) {
                              $query->where('department_id',$departmentId); //filter the categories based on the selected department
                             }
                }
                )

                ->label(__('Category'))
                ->preload() //load all the categories
                ->searchable()
                ->required()
            ]),
            RichEditor::make('description')
            ->required()
            ->toolbarButtons([
                'blockquotes',
                'bold',
                'bulletList',
                'h2',
                'h3',
                'italic',
                'link',
                'orderedList',
                'redo',
                'strike',
                'underline',
                'undo',
                'table',

            ])
            ->columnSpan(2),
            TextInput::make('price')
            ->required()
            ->numeric(),
            TextInput::make('quantity')
            ->integer(),
            Select::make('status')
            ->options(ProductStatusEnum::labels())
            ->default(ProductStatusEnum::Draft->value)
            ->required(),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                ->sortable()
                ->words(10)
                ->searchable(),
                TextColumn::make('status')
                ->badge()
                ->colors(ProductStatusEnum::colors()),
                TextColumn::make('department.name'),
                TextColumn::make('category.name'),
                TextColumn::make('created_at')
                ->dateTime()


            ])
            ->filters([
                SelectFilter::make('status')
                ->options(ProductStatusEnum::labels()),
                SelectFilter::make('department_id')
                ->relationship('department','name')

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        $user =Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::Vendor);
    }
}
