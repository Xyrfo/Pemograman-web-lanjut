<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->toggleable(),
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->size(100)
                    ->toggleable(),
                ToggleColumn::make('is_active')
                    ->label('Status Aktif')
                    ->sortable()
                    ->toggleable(),
                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            // Step B: Record Actions with ActionGroup
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),

                    // Step C: Custom Action — Toggle Active Status
                    Action::make('toggleActive')
                        ->label(fn (Product $record) => $record->is_active ? 'Deactivate' : 'Activate')
                        ->icon(fn (Product $record) => $record->is_active
                            ? Heroicon::OutlinedXCircle
                            : Heroicon::OutlinedCheckCircle)
                        ->color(fn (Product $record) => $record->is_active ? 'danger' : 'success')
                        ->requiresConfirmation()
                        ->modalHeading(fn (Product $record) => $record->is_active
                            ? 'Deactivate Product'
                            : 'Activate Product')
                        ->modalDescription(fn (Product $record) => $record->is_active
                            ? "Are you sure you want to deactivate \"{$record->name}\"?"
                            : "Are you sure you want to activate \"{$record->name}\"?")
                        ->action(function (Product $record) {
                            $record->update(['is_active' => !$record->is_active]);
                            $status = $record->is_active ? 'activated' : 'deactivated';
                            Notification::make()
                                ->title("Product {$status} successfully!")
                                ->success()
                                ->send();
                        }),

                    // Step D: Custom Action — Toggle Featured
                    Action::make('toggleFeatured')
                        ->label(fn (Product $record) => $record->is_featured ? 'Unfeature' : 'Feature')
                        ->icon(fn (Product $record) => $record->is_featured
                            ? Heroicon::OutlinedStar
                            : Heroicon::OutlinedStar)
                        ->color(fn (Product $record) => $record->is_featured ? 'gray' : 'warning')
                        ->action(function (Product $record) {
                            $record->update(['is_featured' => !$record->is_featured]);
                            $status = $record->is_featured ? 'featured' : 'unfeatured';
                            Notification::make()
                                ->title("Product marked as {$status}!")
                                ->success()
                                ->send();
                        }),

                    // Step E: Delete Action with Confirmation
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Product')
                        ->modalDescription(fn (Product $record) => "Are you sure you want to delete \"{$record->name}\"? This action cannot be undone.")
                        ->modalSubmitActionLabel('Yes, Delete'),
                ]),
            ])
            // Step F: Toolbar Actions (Bulk Actions)
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Products')
                        ->modalDescription('Are you sure you want to delete all selected products?'),

                    // Bulk Activate
                    BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(fn (Product $record) => $record->update(['is_active' => true]));
                            Notification::make()
                                ->title($records->count() . ' products activated!')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Deactivate
                    BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon(Heroicon::OutlinedXCircle)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(fn (Product $record) => $record->update(['is_active' => false]));
                            Notification::make()
                                ->title($records->count() . ' products deactivated!')
                                ->warning()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    // Bulk Feature
                    BulkAction::make('feature')
                        ->label('Feature Selected')
                        ->icon(Heroicon::OutlinedStar)
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each(fn (Product $record) => $record->update(['is_featured' => true]));
                            Notification::make()
                                ->title($records->count() . ' products featured!')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
