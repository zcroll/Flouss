<?php

namespace App\Filament\Resources\JobNameResource\Pages;

use App\Enum\LangEnum;
use App\Filament\Resources\JobNameResource;
use App\Filament\Resources\JobTypeResource;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageJobTypes extends ManageRelatedRecords
{
    protected static string $resource = JobNameResource::class;

    protected static string $relationship = 'jobTypes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Manage {$recordTitle} Job Types";
    }

    public function getBreadcrumb(): string
    {
        return 'JobTypes';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Job Types';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('intro')
                    ->required()
                    ->label('Intro')
                    ->maxLength(255),
                Forms\Components\TextInput::make('body')
                    ->required()
                    ->label('Body')
                    ->maxLength(255),
                Forms\Components\ToggleButtons::make('lang')
                    ->inline()
                    ->options(LangEnum::class)
                    ->required(),
                Forms\Components\Select::make('job_name_id')
                    ->relationship('jobName', 'name')
                    ->searchDebounce(10)
                    ->default($this->record->id)


                    ->required()
                    ->searchable()
                    ->label('Job Name'),
            ])
            ->columns(1);
    }

    public function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(1)
            ->schema([
                TextEntry::make('intro')->label('Intro'),
                TextEntry::make('body')->label('Body'),
                TextEntry::make('lang')->label('Language'),
                TextEntry::make('jobName.name')->label('Job Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('intro')
            ->columns([
                Tables\Columns\TextColumn::make('intro')
                    ->label('Intro')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('body')
                    ->label('Body')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('lang')
                    ->label('Language')
                    ->inline()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobName.name')
                    ->label('Job Name')
                    ->searchable()
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
