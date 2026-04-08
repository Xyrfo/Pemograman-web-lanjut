<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //section 1 
                Section::make('Post Details')
                    ->description("Fill in the details of the post")
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        // grouping
                        Group::make([
                            TextInput::make('title'),
                            TextInput::make('slug'),
                            Select::make('category_id')
                                ->relationship("category", "name")
                                ->preload()
                                ->searchable(),
                            ColorPicker::make('color'),
                        ])->columns(2),
                        MarkdownEditor::make('content'),
                    ])->columnSpan(2),

                // Grouping
                Group::make([
                    //section 2
                    Section::make('Image Upload')
                        ->schema([
                            FileUpload::make("image")
                                ->disk("public")
                                ->directory("posts"),
                        ]),
                    //section 3
                    Section::make('Meta Information')
                        ->schema([
                            TagsInput::make('tags'),
                            Checkbox::make('published'),
                        ])->columnSpan(1),
                    DatePicker::make('published_at'),
                ]),
            ])->columns(3);
    }
}
