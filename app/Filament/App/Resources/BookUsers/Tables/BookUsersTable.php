<?php

namespace App\Filament\App\Resources\BookUsers\Tables;

use App\Filament\App\Resources\Books\BookResource;
use App\Filament\Forms\Components\Rating;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookUsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make( name: 'book.title')
                            ->size( size: TextSize::Large)
                            ->weight( weight: FontWeight::SemiBold)
                            ->searchable(),
                        TextColumn::make( name: 'book.author')
                            ->color( color: 'primary')
                            ->searchable(),
                        TextColumn::make('updated_at')
                            ->since()
                            ->extraAttributes(['class' => 'text-xs text-gray-500']),
                        TextColumn::make('status')
                            ->state('Return requested')
                            ->size(TextSize::ExtraSmall)
                            ->badge()
                            ->visible(fn($record) => $record?->return_requested_at && $record->status === 'borrowed'),
                    ])->space(1),
                    ImageColumn::make( name: 'book.image')
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
                DeleteAction::make('delete')
                    ->label('Cancel Request')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->modalHeading('Cancel Book Request')
                    ->modalDescription('Are you sure you want to cancel your request for this book?')
                    ->modalSubmitActionLabel('Yes')
                    ->modalCancelActionLabel('No')
                    ->after(fn($livewire) => $livewire->dispatch('refresh-sidebar'))
                    ->visible(fn($record) => $record->status === 'requested')
                    ->successNotificationTitle('Request Cancelled')
                    ->failureNotificationTitle('Failed to Cancel Request. Try again later'),
                Action::make('return_book')
                    ->label('Return Book')
                    ->button()
                    ->outlined()
                    ->size('xs')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->modalHeading('Did you like the book?')
                    ->modalDescription('Your feedback helps us improve our recommendations.')
                    ->schema([
                        Rating::make('rating')
                            ->required(),
                        RichEditor::make('review')
                            ->label('Write a Review')
                            ->placeholder('Share your thoughts about the book...')
                            ->toolbarButtons(
                                ['bold', 'italic', 'underline', 'h2', 'h3']
                            )
                ])
                ->action(function($record, $data) {
                    $record->update([
                        'return_requested_at' => now(),
                        'rating' => $data['rating'],
                        'review' => $data['review'],
                    ]);
                })
                ->successNotification(
                    Notification::make('return_requested')
                        ->title('Request placed successfully')
                        ->body('Thank you for your feedback! You will be notified when your return is processed')
                        ->icon('heroicon-check-circle')
                )
                ->failureNotification(
                    Notification::make('return_failed')
                        ->title('Failed to request return')
                        ->body('Please try again later. Or contact support if the issue persists.')
                )
                ->visible(fn($record) => $record->status === 'borrowed' && !$record->return_requested_at),
            ])
            ->emptyStateHeading(
                function($livewire) {
                    return match ($livewire->activeTab) {
                        'requested' => 'You have not requested any books yet.',
                        'borrowed' => 'You have not borrowed any books yet.',
                        'returned' => 'You have not returned any books yet.',
                        default => 'No books found.',
                    };
                }
            )
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateActions([
                Action::make('browse_books')
                    ->label('Browse Books')
                    ->url(BookResource::getUrl())
                    ->button()
            ])
            ->searchPlaceholder('Search by title or author')
            ->paginated([10]);
    }
}
