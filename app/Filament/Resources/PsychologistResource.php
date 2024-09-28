<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PsychologistResource\Pages;
use App\Filament\Resources\PsychologistResource\RelationManagers;
use App\Models\Psychologist;
use Filament\Forms;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PsychologistResource extends Resource
{
    protected static ?string $model = Psychologist::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

//    protected static ?string $navigationLabel = "Lokasi";
//
//    protected static ?string $pluralLabel= "Lokasi";
//    protected static ?string $label = "Lokasi";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('image')
                            ->collection('image')
                            ->maxSize(512)
                            ->image()
                        ,
                        Forms\Components\TextInput::make("name")
                            ->placeholder("Name")
                            ->required(),
                        Forms\Components\TextInput::make("email")
                            ->placeholder("Email")
                            ->required()
                            ->email(),
                        Forms\Components\TextInput::make("phone_number")
                            ->placeholder("Phone")
                            ->required(),
                        Forms\Components\Textarea::make("address")
                            ->placeholder("Address")
                            ->required(),
                        Map::make('location')
                            ->label('Location')
                            ->columnSpanFull()
                            ->default([
                                'lat' => -7.765257771462874,
                                'lng' => 110.37255360318393
                            ])
                            ->afterStateUpdated(function (Set $set, ?array $state): void {
                                $set('latitude', $state['lat']);
                                $set('longitude', $state['lng']);
                            })
                            ->afterStateHydrated(function ($state, $record, Set $set): void {
                                $set('location', ['lat' => $record?->latitude ?? -7.765257771462874, 'lng' => $record?->longitude ?? 110.37255360318393]);
                            })
                            ->extraStyles([
                                'min-height: 40vh',
                                'border-radius: 0px'
                            ])
                            ->liveLocation()
                            ->showMarker()
                            ->markerColor("#FF0000")
                            ->showFullscreenControl()
                            ->showZoomControl()
                            ->draggable()
                            ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                            ->zoom(15)
                            ->detectRetina()
                            ->showMyLocationButton()
                            ->extraTileControl([])
                            ->extraControl([
                                'zoomDelta' => 1,
                                'zoomSnap' => 2,
                            ]),
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('latitude')
                                    ->readOnly()
                                ,

                                Forms\Components\TextInput::make('longitude')
                                    ->readOnly()
                            ]),

                    ])
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make("name")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("email")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("phone_number")
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make("address")
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListPsychologists::route('/'),
            'create' => Pages\CreatePsychologist::route('/create'),
            'edit' => Pages\EditPsychologist::route('/{record}/edit'),
        ];
    }
}
