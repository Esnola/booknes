<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\Widget;

class CurrentlyReading extends Widget
{
    protected string $view = 'filament.app.widgets.currently-reading';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'md' => 3,
    ];

    public array $books = [];

    public function mount(): void
    {
        $this->books = auth()->user()->books()
            ->where('status', 'borrowed')
            ->get()
            ->toArray();
    }
}
