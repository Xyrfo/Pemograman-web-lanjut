<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Menghilangkan default button karena kita menggunakan Wizard
     * dengan submit button sendiri
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * Override method untuk menampilkan notification setelah update
     */
    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Produk berhasil diperbarui!')
            ->body('Data produk telah diupdate.')
            ->send();
    }

    /**
     * Redirect ke halaman list setelah berhasil save
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
