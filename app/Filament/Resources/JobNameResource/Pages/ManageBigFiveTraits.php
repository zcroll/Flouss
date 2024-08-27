<?php

namespace App\Filament\Resources\JobNameResource\Pages;

use App\Enum\LangEnum;
use App\Filament\Resources\JobNameResource;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageBigFiveTraits extends ManageRelatedRecords
{
    protected static string $resource = JobNameResource::class;

    protected static string $relationship = 'bigFiveTraits';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Manage {$recordTitle} Big Five Traits";
    }

    public function getBreadcrumb(): string
    {
        return 'Big Five Traits';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Big Five Traits';
    }

    public function form(Form $form): Form
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
                Forms\Components\ToggleButtons::make('lang')
                    ->inline()
                    ->options(LangEnum::class)
                    ->required(),
                Forms\Components\Select::make('job_name_id')
                    ->relationship('jobName', 'name')
                    ->searchDebounce(10)
                    ->default($this->record->id)

                    ->searchDebounce(10)


                    ->searchable()

                    ->required(),
            ])
            ->columns(1);
    }

    public function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('description')->label('Description'),
                TextEntry::make('openness')->label('Openness'),
                TextEntry::make('conscientiousness')->label('Conscientiousness'),
                TextEntry::make('extraversion')->label('Extraversion'),
                TextEntry::make('agreeableness')->label('Agreeableness'),
                TextEntry::make('neuroticism')->label('Neuroticism'),
                TextEntry::make('lang')->label('Language'),
                TextEntry::make('jobName.name')->label('Job Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
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
                Tables\Columns\TextColumn::make('lang')
                    ->label('Language')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobName.name')
                    ->sortable(),
            ])
            ->filters([])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }
}
