<?php

namespace App\Filament\App\Resources\BookUsers\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('book_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required(),
                TextInput::make('rating')
                    ->numeric(),
                Textarea::make('review')
                    ->columnSpanFull(),
                DateTimePicker::make('requested_at'),
                DateTimePicker::make('borrowed_at'),
                DateTimePicker::make('returned_at'),
                DateTimePicker::make('return_requested_at'),
            ]);
    }
}
