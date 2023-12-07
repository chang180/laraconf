<?php

namespace App\Models;

use App\Models\Talk;
use App\Enums\Region;
use App\Models\Venue;
use App\Models\Speaker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm()
    {
        return [
            Section::make('Conference Details')
                ->collapsible()
                ->description('Provide some basic information about the conference.')
                ->icon('heroicon-o-information-circle')
                ->columns(['md'=>2, 'lg'=>3])
                ->schema([
                    TextInput::make('name')
                        ->columnSpanFull()
                        ->label('Conference Name')
                        ->default('My Conference')
                        ->required()
                        ->maxLength(60),
                    RichEditor::make('description')
                        ->columnSpanFull()
                        ->required(),
                    DateTimePicker::make('start_date')
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->required(),
                    Fieldset::make('Status')
                        ->columns(1)
                        ->schema([
                            TextInput::make('status')
                                ->required(),
                            Toggle::make('is_published')
                                ->required(),
                            CheckboxList::make('speakers')
                                ->relationship('speakers', 'name')
                                ->options(Speaker::pluck('name', 'id'))
                                ->required(),
                        ])
                ]),
            Section::make('Location')
                ->columns(2)
                ->schema([
                    Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::class),
                    Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->editOptionForm(Venue::getForm())
                        ->createOptionForm(Venue::getForm())
                        ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Get $get) {
                            return $query->where('region', $get('region'));
                        }),
                ]),
        ];
    }
}
