<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Checkbox;
use Filament\Actions\Action;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    // Step 1: Product Info
                    Step::make('Product Info')
                        ->description('Isi informasi dasar produk')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Group::make([
                                TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required(),
                                TextInput::make('sku')
                                    ->label('SKU')
                                    ->required()
                                    ->unique('products', 'sku', ignoreRecord: true),
                            ])->columns(2),
                            MarkdownEditor::make('description')
                                ->label('Deskripsi')
                                ->columnSpanFull(),
                        ]),

                    // Step 2: Pricing & Stock
                    Step::make('Pricing & Stock')
                        ->description('Isi harga dan jumlah stok')
                        ->icon('heroicon-o-tag')
                        ->schema([
                            Group::make([
                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->inputMode('decimal'),
                                TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->required()
                                    ->minValue(0),
                            ])->columns(2),
                        ]),

                    // Step 3: Media & Status
                    Step::make('Media & Status')
                        ->description('Upload gambar dan atur status')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            FileUpload::make('image')
                                ->label('Gambar Produk')
                                ->disk('public')
                                ->directory('products')
                                ->image()
                                ->visibility('public')
                                ->nullable(),
                            Group::make([
                                Checkbox::make('is_active')
                                    ->label('Aktif'),
                                Checkbox::make('is_featured')
                                    ->label('Featured'),
                            ])->columns(2),
                        ]),
                ])
                ->columnSpanFull()
                ->submitAction(
                    Action::make('save')
                        ->label('Simpan Produk')
                        ->button()
                        ->color('primary')
                        ->submit('save')
                )
            ]);
    }
}
