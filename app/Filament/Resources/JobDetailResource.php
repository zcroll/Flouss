<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobDetailResource\Pages;
use App\Filament\Resources\JobDetailResource\RelationManagers;
use App\Models\JobDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobDetailResource extends Resource
{
    protected static ?string $model = JobDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('role_description_main')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('role_description_secondary')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('average_salary')
                    ->numeric(),
                Forms\Components\TextInput::make('career_growth')
                    ->maxLength(255),
                Forms\Components\TextInput::make('job_satisfaction')
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('average_salary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('career_growth')
                    ->searchable(),
                Tables\Columns\TextColumn::make('job_satisfaction')
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
            'index' => Pages\ListJobDetails::route('/'),
            'create' => Pages\CreateJobDetail::route('/create'),
            'edit' => Pages\EditJobDetail::route('/{record}/edit'),
        ];
    }
}
