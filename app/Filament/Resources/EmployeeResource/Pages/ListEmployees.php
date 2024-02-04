<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use App\Models\Employee;
use Filament\Resources\Pages\ListRecords;
// use Filament\Infolists\Components\Tabs\Tab;
use Filament\Tables\Actions\CreateAction;
use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\ListRecords\Tab;
use Illuminate\Contracts\Database\Eloquent\Builder;


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
            'This week'=> Tab::make()
            ->modifyQueryUsing(fn ( Builder $query ) =>$query->where('date_of_hire', '≥',now()->subWeek())  )
            ->badge(Employee::query()->where('date_of_hire', '≥',now()->subWeek())->count() ),
            'This month'=> Tab::make()
            ->modifyQueryUsing(fn ( Builder $query ) =>$query->where('date_of_hire', '≥',now()->subMonth())  )
            ->badge(Employee::query()->where('date_of_hire', '≥',now()->subMonth())->count() ),
            'This year'=> Tab::make()
            ->modifyQueryUsing(fn ( Builder $query ) =>$query->where('date_of_hire', '≥',now()->subYear())  )
            ->badge(Employee::query()->where('date_of_hire', '≥',now()->subWeek())->count() ),


        ];
    }
}
