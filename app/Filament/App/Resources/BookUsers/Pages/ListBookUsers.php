<?php

namespace App\Filament\App\Resources\BookUsers\Pages;

use App\Filament\App\Resources\BookUsers\BookUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBookUsers extends ListRecords
{
    protected static string $resource = BookUserResource::class;

    public function getTabs(): array
    {
        return [
            'requested' => Tab::make('Requested Books')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'requested'))
                ->icon('tabler-clock-plus')
                ->badge(
                    fn () => auth()->user()->books()->where('status', 'requested')->count()
                ),
            'borrowed' => Tab::make('Currently Reading')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'borrowed'))
                ->icon('tabler-book')
                ->badge(
                    fn () => auth()->user()->books()->where('status', 'borrowed')->count()
                ),
            'returned' => Tab::make('Past Reads')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'returned'))
                ->icon('tabler-book-2')
                ->badge(
                    fn () => auth()->user()->books()->where('status', 'returned')->count()
                ),
        ];
    }
}
