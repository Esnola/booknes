<?php
  
  namespace App\Filament\App\Resources\Books\Tables;
  
  use Filament\Actions\BulkActionGroup;
  use Filament\Actions\DeleteBulkAction;
  use Filament\Actions\EditAction;
  use Filament\Actions\ViewAction;
  use Filament\Tables\Columns\ImageColumn;
  use Filament\Tables\Columns\TextColumn;
  use Filament\Tables\Table;
  
  class BooksTable
  {
    public static function configure(Table $table): Table
    {
      return $table
        ->columns([
          ImageColumn::make('image'),
          TextColumn::make('title')
            ->searchable(),
          TextColumn::make('author')
            ->searchable(),
          TextColumn::make('created_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),
          TextColumn::make('updated_at')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
          //
        ])
        ->recordActions([
          ViewAction::make(),
          EditAction::make(),
        ])
        ->toolbarActions([
          BulkActionGroup::make([
            DeleteBulkAction::make(),
          ]),
        ]);
    }
  }
