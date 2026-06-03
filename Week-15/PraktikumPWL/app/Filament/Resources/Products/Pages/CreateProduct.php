<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    /**
     * Menghilangkan default button karena kita menggunakan Wizard
     * dengan submit button sendiri
     */
    protected function getFormActions(): array
    {
        return [];
    }

    /**
     * Override method untuk menampilkan notification dan redirect setelah save
     */
    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title('Produk berhasil dibuat!')
            ->body('Data produk telah disimpan ke database.')
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
