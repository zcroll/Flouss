<?php

namespace App\Filament\Resources;

use App\Enum\LangEnum;
use App\Filament\Resources\JobNameResource\Pages;
use App\Filament\Resources\JobNameResource\RelationManagers;
use App\Models\JobName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\Pages\Page;

class JobNameResource extends Resource
{
    protected static ?string $model = JobName::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('education_level')
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('lang')
                    ->inline()
                    ->options(LangEnum::class)
                    ->required(),
                Forms\Components\TextInput::make('image')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('education_level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang'),
                Tables\Columns\ImageColumn::make('image'),
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
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([

            JobNameResource\Pages\ManageJobTypes::class,
            JobNameResource\Pages\ManageJobDetails::class,
            JobNameResource\Pages\ManageJobResonsibilities::class,
            JobNameResource\Pages\ManageHollandCodeTraits::class,
            JobNameResource\Pages\ManageBigFiveTraits::class,
            JobNameResource\Pages\ManageWorkEnvironments::class,
            JobNameResource\Pages\ManageWorkplaces::class,

        ]);}

        public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobNames::route('/'),
            'edit' => Pages\EditJobName::route('/{record}/edit'),
            'types'=> Pages\ManageJobTypes::route('/{record}/types'),
            'details'=> Pages\ManageJobDetails::route('/{record}/details'),
            'responsibilities'=> Pages\ManageJobResonsibilities::route('/{record}/responsibilities'),
            'hollandCodeTraits'=> Pages\ManageHollandCodeTraits::route('/{record}/hollandCodeTraits'),
            'bigFiveTraits'=> Pages\ManageBigFiveTraits::route('/{record}/bigFiveTraits'),
            'workEnvironments'=> Pages\ManageWorkEnvironments::route('/{record}/workEnvironments'),
            'workplaces'=> Pages\ManageWorkplaces::route('/{record}/workplaces'),
        ];
    }
}
