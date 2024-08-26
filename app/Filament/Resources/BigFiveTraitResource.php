<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BigFiveTraitResource\Pages;
use App\Filament\Resources\BigFiveTraitResource\RelationManagers;
use App\Models\BigFiveTrait;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BigFiveTraitResource extends Resource
{
    protected static ?string $model = BigFiveTrait::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('openness')
                    ->numeric(),
                Forms\Components\TextInput::make('conscientiousness')
                    ->numeric(),
                Forms\Components\TextInput::make('extraversion')
                    ->numeric(),
                Forms\Components\TextInput::make('agreeableness')
                    ->numeric(),
                Forms\Components\TextInput::make('neuroticism')
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
                Tables\Columns\TextColumn::make('openness')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conscientiousness')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('extraversion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agreeableness')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('neuroticism')
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
            'index' => Pages\ListBigFiveTraits::route('/'),
            'create' => Pages\CreateBigFiveTrait::route('/create'),
            'edit' => Pages\EditBigFiveTrait::route('/{record}/edit'),
        ];
    }
}
