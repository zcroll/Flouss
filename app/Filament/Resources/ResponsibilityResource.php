<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResponsibilityResource\Pages;
use App\Filament\Resources\ResponsibilityResource\RelationManagers;
use App\Models\Responsibility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResponsibilityResource extends Resource
{
    protected static ?string $model = Responsibility::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('intro')
                    ->maxLength(255),
                Forms\Components\Textarea::make('body')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('lang')
                    ->required(),
                Forms\Components\Select::make('job_name_id')
                    ->relationship('jobName', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('intro')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang'),
                Tables\Columns\TextColumn::make('jobName.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResponsibilities::route('/'),
            'create' => Pages\CreateResponsibility::route('/create'),
            'edit' => Pages\EditResponsibility::route('/{record}/edit'),
        ];
    }
}
