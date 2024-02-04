<?php

namespace App\Filament\Resources\CityResource\RelationManagers;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
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
                ->relationship(name: 'country', titleAttribute: 'name')
                ->preload()
                ->live()
                ->searchable()
                ->required()
                ->afterStateUpdated(
                    function (Set $set) {
                        $set('state_id', null);
                        $set('city_id', null);
                    }
                ),

            Select::make('state_id')
                ->options(
                    fn(Get $get)
                    => State::query()
                        ->where('country_id', $get('country_id'))
                        ->pluck('name', 'id')

                )
                ->preload()
                ->live()
                ->searchable()
                ->required()
                ->afterStateUpdated(
                    fn(Set $set) => $set('city_id', null)
                )
            ,

            Select::make('city_id')
                ->options(
                    fn(Get $get)
                    => City::query()
                        ->where('state_id', $get('state_id'))
                        ->pluck('name', 'id')
                )
                ->preload()
                ->live()
                ->searchable()
                ->required(),

            Select::make('department_id')
                ->relationship('department', 'name')
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function isReadOnly(): bool
    {
        return false;
    }



}
