<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReadingStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'md' => 6,
    ];

    protected function getStats(): array
    {
        return [
            Stat::make('Reading since', (int) auth()->user()->created_at->diffInDays(now()))
                ->description('Days since you joined')
                ->descriptionIcon('tabler-calendar')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'h-full',
                ]),
            Stat::make('Books read', auth()->user()->books()->where('status', 'returned')->count())
                ->description('Total books till date')
                ->descriptionIcon('tabler-book')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'h-full',
                ]),
            Stat::make('Reading rate', function () {
                $monthsSinceJoining = auth()->user()->created_at->diffInDays(now())/30;
                $booksRead = auth()->user()->books()->where('status', 'returned')->count();
                if ($monthsSinceJoining === 0) {
                    return 0;
                }
                return round($booksRead / $monthsSinceJoining);
            })
                ->description('Average books per month')
                ->descriptionIcon('tabler-chart-line')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'h-full',
                ]),
        ];
    }
}
