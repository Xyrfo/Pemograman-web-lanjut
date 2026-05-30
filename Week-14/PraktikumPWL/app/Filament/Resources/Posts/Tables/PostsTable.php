<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                ColorColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->disk('public')
                    ->toggleable(),
                ToggleColumn::make('published')
                    ->label('Published')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->schema([
                        DatePicker::make('created_at')
                            ->label('Select Date'),
                    ])
                    ->query(function ($query, $data) {
                        return $query->when(
                            $data['created_at'],
                            fn ($query, $date) => $query->whereDate('created_at', $date)
                        );
                    }),
                SelectFilter::make('category_id')
                    ->label('Select Category')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            // Step B: Record Actions (per-row actions)
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),

                    // Step C: Custom Action — Clone Post
                    Action::make('clone')
                        ->label('Clone')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Clone Post')
                        ->modalDescription('Are you sure you want to clone this post?')
                        ->modalSubmitActionLabel('Yes, Clone it')
                        ->action(function (Post $record) {
                            $clone = $record->replicate();
                            $clone->title = '[Clone] ' . $record->title;
                            $clone->slug = $record->slug . '-clone-' . time();
                            $clone->published = false;
                            $clone->save();

                            Notification::make()
                                ->title('Post cloned successfully!')
                                ->success()
                                ->send();
                        }),

                    // Step D: Delete Action with Confirmation
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Post')
                        ->modalDescription(fn (Post $record) => "Are you sure you want to delete \"{$record->title}\"? This action cannot be undone.")
                        ->modalSubmitActionLabel('Yes, Delete'),
                ]),
            ])
            // Step E: Toolbar Actions (Header & Bulk)
            ->toolbarActions([
                // Step F: Bulk Actions
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Posts')
                        ->modalDescription('Are you sure you want to delete all selected posts? This action cannot be undone.'),

                    // Bulk Publish
                    BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon(Heroicon::OutlinedCheck)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Publish Selected Posts')
                        ->modalDescription('Are you sure you want to publish all selected posts?')
                        ->action(function (Collection $records) {
                            $records->each(fn (Post $record) => $record->update(['published' => true]));
                            Notification::make()
                                ->title($records->count() . ' posts published successfully!')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Unpublish
                    BulkAction::make('unpublish')
                        ->label('Unpublish Selected')
                        ->icon(Heroicon::OutlinedXMark)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Unpublish Selected Posts')
                        ->modalDescription('Are you sure you want to unpublish all selected posts?')
                        ->action(function (Collection $records) {
                            $records->each(fn (Post $record) => $record->update(['published' => false]));
                            Notification::make()
                                ->title($records->count() . ' posts unpublished successfully!')
                                ->warning()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
