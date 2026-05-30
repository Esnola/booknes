<?php

namespace App\Filament\App\Resources\Books\Schemas;

use App\Filament\App\Resources\BookUsers\BookUserResource;
use App\Filament\Infolists\Components\Rating;
use App\Models\BookUser;
use Filament\Actions\Action;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columns([
                        'sm' => 4,
                        'xl' => 5,
                    ])
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columnSpan([
                                'sm' => 3,
                                'xl' => 4,
                            ])
                            ->schema([
                                TextEntry::make('title')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->extraAttributes([
                                        'class' => 'text-3xl xl:text-4xl font-bold',
                                    ]),
                                TextEntry::make('author')
                                    ->hiddenLabel()
                                    ->columnSpanFull()
                                    ->extraAttributes([
                                        'class' => 'text-primary-600 font-medium text-lg'
                                    ]),
                                TextEntry::make('description')
                                    ->hiddenLabel()
                                    ->placeholder('-')
                                    ->columnSpanFull()
                                    ->extraAttributes(['class' => 'text-base']),
                                Rating::make('average_rating')
                                    ->label('Rating')
                                    ->placeholder('No rating'),
                                TextEntry::make('borrowed_count')
                                    ->label('Borrowed')
                                    ->state(fn ($record) => $record->users()->count().' times'),
                                Actions::make([
                                    Action::make('request')
                                        ->label('Request Book')
                                        ->button()
                                        ->icon('heroicon-o-clock')
                                        ->action(function ($record) {
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
                                        ->visible(fn ($record) => !in_array($record?->currentBorrow?->status, ['requested', 'borrowed']))
                                ])
                            ]),
                        ImageEntry::make('image')
                            ->hiddenLabel()
                            ->imageWidth('100%')
                            ->imageHeight('auto')
                            ->extraImgAttributes([
                                'class' => 'rounded-lg shadow-md',
                            ])
                            ->placeholder('-'),
                        Text::make("User Reviews")
                            ->extraAttributes([
                                'class' => 'text-xl font-semibold'
                            ]),
                        RepeatableEntry::make('reviews')
                            ->hiddenLabel()
                            ->state(fn ($record) => $record->reviews()->latest()->take(4)->get())
                            ->schema([
                                TextEntry::make('review')
                                    ->hiddenLabel()
                                    ->html(),
                                TextEntry::make('user.name')
                                    ->hiddenLabel()
                                    ->extraAttributes([
                                        'class' => 'italic text-sm text-primary-600'
                                    ])
                            ])
                            ->grid([
                                'default' => 1,
                                'md' => 2,
                            ])
                            ->contained(false)
                            ->columnSpanFull()
                    ])
                    ->dense()
            ]);
    }
}
