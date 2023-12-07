<?php

namespace App\Models;

use App\Models\Conference;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm()
    {
        return [
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Textarea::make('bio')
                ->required()
                ->maxLength(65535)
                ->columnSpanFull(),
            TextInput::make('twitter_handle')
                ->required()
                ->maxLength(255),
            CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options([
                    'developer' => 'Certified Laravel Developer',
                    'front-end' => 'Certified Laravel Frontend Developer',
                    'back-end' => 'Certified Laravel Backend Developer',
                    'fullstack' => 'Certified Laravel Fullstack Developer',
                ])
                ->descriptions([
                    'developer' => 'Certified Laravel Developer',
                    'fullstack' => 'Certified Laravel Fullstack Developer',
                ])
                ->columns(3),
        ];
    }
}
