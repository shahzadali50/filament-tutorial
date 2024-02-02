<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\CreateAction;
// use Filament\Infolists\Components\Tabs\Tab;
use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\ListRecords\Tab;


class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        return[
            'All'=> Tab::make(),

        ];
    }
}
