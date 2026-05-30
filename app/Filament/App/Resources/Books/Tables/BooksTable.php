<?php

namespace App\Filament\App\Resources\Books\Tables;

use App\Filament\App\Resources\BookUsers\BookUserResource;
use App\Filament\Tables\Columns\RatingColumn;
use App\Models\BookUser;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        Stack::make([
                            TextColumn::make( name: 'title')
                                ->size( size: TextSize::Large)
                                ->weight( weight: FontWeight::SemiBold)
                                ->searchable(),
                            TextColumn::make( name: 'author')
                                ->color( color: 'primary')
                                ->searchable(),
                        ]),
                        RatingColumn::make( name: 'average_rating'),
                        TextColumn::make('status')
                            ->state(fn($record) => $record?->currentBorrow?->status)
                            ->formatStateUsing(
                                function($state) {
                                    return match ($state) {
                                        'requested' => 'Requested',
                                        'borrowed' => 'Currently Reading',
                                        'returned' => 'Read Before',
                                        default => null,
                                    };
                                }
                            )
                        ->badge()
                        ->colors([
                            'warning' => 'requested',
                            'success' => 'borrowed',
                            'gray' => 'returned',
                        ])
                    ])->space(3),
                    ImageColumn::make( name: 'image')
                        ->imageWidth( width: 80)
                        ->imageHeight( height: 'auto')
                        ->grow( condition: false),
                ])
            ])->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3
            ])
            ->recordActions([
                Action::make('request')
                    ->label('Request Book')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->icon('heroicon-o-clock')
                    ->action(function($record) {
                        BookUser::updateOrCreate(
                            [
                                'user_id' => auth()->id(),
                                'book_id' => $record->id,
                            ],
                            [
                                'status' => 'requested',
                                'requested_at' => now(),
                            ]
                        );
                    })
                    ->after(fn($livewire) => $livewire->dispatch('refresh-sidebar'))
                    ->successNotification(
                        Notification::make()
                            ->title('Book Requested')
                            ->actions([
                                Action::make('view_requests')
                                    ->label('View all requests')
                                    ->url(BookUserResource::getUrl())
                                    ->button()
                                    ->size('xs'),
                            ])
                            ->persistent()
                    )
                    ->failureNotification(
                        Notification::make()
                            ->title('Failed to request book. Try again later.')
                    )
                    ->visible(fn($record) => !in_array($record?->currentBorrow?->status, ['requested', 'borrowed'])),
            ])
            ->searchPlaceholder('Search by title or author')
            ->paginated([12]);
    }
}
