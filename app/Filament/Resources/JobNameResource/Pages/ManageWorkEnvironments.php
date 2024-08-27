<?php

namespace App\Filament\Resources\JobNameResource\Pages;

use App\Enum\LangEnum;
use App\Filament\Resources\JobNameResource;
use App\Models\JobName;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class ManageWorkEnvironments extends ManageRelatedRecords
{
    protected static string $resource = JobNameResource::class;

    protected static string $relationship = 'workEnvironments';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
 public Model | int | string | null $record ;
    public function getTitle(): string | Htmlable
    {
        ds($this->record);

        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Manage {$recordTitle} Work Environments";
    }

    public function getBreadcrumb(): string
    {
        return 'Work Environments';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Work Environments';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('score')
                    ->numeric(),
                Forms\Components\ToggleButtons::make('lang')
                    ->inline()
                    ->options(LangEnum::class)
                    ->required(),
                Forms\Components\Select::make('job_name_id')
                    ->relationship('jobName', 'name')
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
                TextEntry::make('name')->label('Name'),
                TextEntry::make('description')->label('Description'),
                TextEntry::make('score')->label('Score'),
                TextEntry::make('lang')->label('Language'),
                TextEntry::make('jobName.name')->label('Job Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
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
