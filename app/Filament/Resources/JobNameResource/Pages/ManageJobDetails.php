<?php

namespace App\Filament\Resources\JobNameResource\Pages;

use App\Enum\LangEnum;
use App\Filament\Resources\JobNameResource;
use App\Filament\Resources\JobDetailResource;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ManageJobDetails extends ManageRelatedRecords
{
    protected static string $resource = JobNameResource::class;

    protected static string $relationship = 'jobDetails';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Manage {$recordTitle} Job Details";
    }

    public function getBreadcrumb(): string
    {
        return 'JobDetails';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Job Details';
    }

    public function form(Form $form): Form
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
                Forms\Components\ToggleButtons::make('lang')
                    ->inline()
                    ->options(LangEnum::class)
                    ->required(),
                Forms\Components\Select::make('job_name_id')
                    ->relationship('jobName', 'name')
                    ->searchDebounce(10)
                    ->default($this->record->id)

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
                TextEntry::make('role_description_main')->label('Main Role Description'),
                TextEntry::make('role_description_secondary')->label('Secondary Role Description'),
                TextEntry::make('average_salary')->label('Average Salary'),
                TextEntry::make('career_growth')->label('Career Growth'),
                TextEntry::make('job_satisfaction')->label('Job Satisfaction'),
                TextEntry::make('lang')->label('Language'),
                TextEntry::make('jobName.name')->label('Job Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('role_description_main')
            ->columns([
                Tables\Columns\TextColumn::make('role_description_main')
                    ->label('Main Role Description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role_description_secondary')
                    ->label('Secondary Role Description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('average_salary')
                    ->label('Average Salary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('career_growth')
                    ->label('Career Growth')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job_satisfaction')
                    ->label('Job Satisfaction')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lang')
                    ->label('Language')
                    ->searchable()
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
