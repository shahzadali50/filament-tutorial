<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Employee Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User Name')
                ->description('put the user name Detail')
                ->schema([
                    Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->label('first-name'),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('middle_name')
                    ->required()
                    ->maxLength(255),

                ])->columns(3),
                    Select::make('country_id')
                        ->relationship(name:'country',titleAttribute:'name')
                        ->preload()
                        ->live()
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(
                            function(Set $set){
                            $set('state_id',null);
                            $set('city_id',null);
                            }
                        ),

                    Select::make('state_id')
                        ->options(
                            fn(Get $get)
                            =>State::query()
                            ->where('country_id',$get('country_id'))
                            ->pluck('name','id')

                        )
                        ->preload()
                        ->live()
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(
                            fn(Set $set) => $set('city_id',null)
                        )
                        ,

                        Select::make('city_id')
                        ->options(
                            fn(Get $get)
                            =>City::query()
                            ->where('state_id',$get('state_id'))
                            ->pluck('name','id')
                        )
                        ->preload()
                        ->live()
                        ->searchable()
                        ->required(),

                        Select::make('department_id')
                        ->relationship('department','name')
                        ->required(),


                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->required(),
                Forms\Components\DatePicker::make('date_of_hire')
                ->native(false)
                ->displayFormat('d/m/Y')
                    ->required(),
            ])->columns(3);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist

        ->schema([
            Section::make('Employee Info')
        ->schema([

            TextEntry::make('first_name')->label('first_name'),
            TextEntry::make('middle_name')->label('middle_name'),
            TextEntry::make('last_name')->label('last_name'),




        ])->columns(3),
        Section::make('Relationship')
        ->schema([

            TextEntry::make('country.name')->label('country'),
            TextEntry::make('state.name')->label('state'),
            TextEntry::make('city.name')->label('city'),




        ])->columns(3),
        Section::make('User Adress')
        ->schema([

            TextEntry::make('address')->label('address'),
            TextEntry::make('zip_code')->label('zip_code'),
            




        ])->columns(3),

    ]);

    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                ->searchable(),
            Tables\Columns\TextColumn::make('last_name')
                ->searchable(),
            Tables\Columns\TextColumn::make('middle_name')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('country.name')
                    ->numeric()
                    ->sortable()

                    ->label('country Name'),
                Tables\Columns\TextColumn::make('state.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zip_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('date_of_hire')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
