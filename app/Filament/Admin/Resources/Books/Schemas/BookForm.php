<?php

namespace App\Filament\Admin\Resources\Books\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('author')
                    ->required(),
                FileUpload::make('image')
                    ->image(),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
