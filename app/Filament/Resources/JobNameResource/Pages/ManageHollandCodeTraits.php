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

class ManageHollandCodeTraits extends ManageRelatedRecords
{
    protected static string $resource = JobNameResource::class;

    protected static string $relationship = 'hollandCodeTraits';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public function getTitle(): string | Htmlable
    {
        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Manage {$recordTitle} Holland Code Traits";
    }

    public function getBreadcrumb(): string
    {
        return 'Holland Code Traits';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Holland Code Traits';
    }

    public function form(Form $form): Form
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
                TextEntry::make('description')->label('Description'),
                TextEntry::make('realistic')->label('Realistic'),
                TextEntry::make('investigative')->label('Investigative'),
                TextEntry::make('artistic')->label('Artistic'),
                TextEntry::make('social')->label('Social'),
                TextEntry::make('enterprising')->label('Enterprising'),
                TextEntry::make('conventional')->label('Conventional'),
                TextEntry::make('lang')->label('Language'),
                TextEntry::make('jobName.name')->label('Job Name'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
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
