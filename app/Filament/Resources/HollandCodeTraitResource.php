<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HollandCodeTraitResource\Pages;
use App\Filament\Resources\HollandCodeTraitResource\RelationManagers;
use App\Models\HollandCodeTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HollandCodeTraitResource extends Resource
{
    protected static ?string $model = HollandCodeTrait::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('realistic')
                    ->numeric(),
                Forms\Components\TextInput::make('investigative')
                    ->numeric(),
                Forms\Components\TextInput::make('artistic')
                    ->numeric(),
                Forms\Components\TextInput::make('social')
                    ->numeric(),
                Forms\Components\TextInput::make('enterprising')
                    ->numeric(),
                Forms\Components\TextInput::make('conventional')
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('realistic')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('investigative')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('artistic')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('social')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('enterprising')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conventional')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListHollandCodeTraits::route('/'),
            'create' => Pages\CreateHollandCodeTrait::route('/create'),
            'edit' => Pages\EditHollandCodeTrait::route('/{record}/edit'),
        ];
    }
}
