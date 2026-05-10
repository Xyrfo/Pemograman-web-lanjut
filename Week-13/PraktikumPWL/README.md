# 🎓 Pertemuan 13 - Table Actions in Depth pada Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

## 📚 Capaian Pembelajaran

1. Memahami konsep Table Actions pada Filament (Record, Bulk, Toolbar)
2. Menggunakan `ActionGroup` untuk mengelompokkan aksi per-baris
3. Membuat Custom Action dengan konfirmasi modal
4. Mengimplementasikan Bulk Action untuk operasi massal
5. Menambahkan Notification setelah aksi berhasil
6. Menerapkan Table Actions pada resource Post dan Product

---

## 🎯 Latar Belakang (Bagian A)

Pada pertemuan sebelumnya kita telah menggunakan Toggle Column dan toggleable(). Namun dalam pengelolaan data, admin sering kali membutuhkan:
- **Aksi per-baris** (record actions) — View, Edit, Delete, Clone pada setiap record
- **Aksi massal** (bulk actions) — Publish/Unpublish, Activate/Deactivate banyak record sekaligus
- **Konfirmasi modal** — Mencegah aksi berbahaya secara tidak sengaja
- **Notifikasi** — Memberikan feedback setelah aksi berhasil

Filament menyediakan sistem Actions yang powerful dan unified di namespace `Filament\Actions`.

### Jenis Table Actions di Filament

| Jenis | Method | Deskripsi |
|-------|--------|-----------|
| **Record Actions** | `->recordActions([])` | Aksi per-baris, muncul di setiap row tabel |
| **Bulk Actions** | `BulkActionGroup::make([])` | Aksi massal setelah memilih beberapa record |
| **Toolbar Actions** | `->toolbarActions([])` | Aksi di toolbar tabel (header area) |

---

## 🔧 Langkah Praktikum

### Step B: Record Actions dengan ActionGroup

**File:** `app/Filament/Resources/Posts/Tables/PostsTable.php`

**Import yang dibutuhkan:**
```php
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
```

**Tambahkan ActionGroup pada recordActions:**
```php
->recordActions([
    ActionGroup::make([
        ViewAction::make()
            ->color('info'),
        EditAction::make()
            ->color('warning'),
        DeleteAction::make()
            ->requiresConfirmation(),
    ]),
])
```

Hasil:
- Aksi View, Edit, Delete dikelompokkan dalam dropdown menu (⋮)
- Setiap aksi memiliki warna yang berbeda untuk memudahkan identifikasi
- Tampilan lebih rapi dibandingkan menampilkan semua tombol secara terpisah

---

### Step C: Custom Action — Clone Post

Tambahkan custom action untuk menduplikasi post:

```php
use App\Models\Post;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

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
```

Hasil:
- Tombol "Clone" muncul di ActionGroup setiap baris
- Klik Clone → muncul modal konfirmasi
- Post baru dibuat dengan prefix "[Clone]" dan status unpublished
- Notifikasi sukses ditampilkan setelah clone berhasil

---

### Step D: Delete Action dengan Konfirmasi Modal

Tambahkan DeleteAction dengan modal konfirmasi yang informatif:

```php
DeleteAction::make()
    ->requiresConfirmation()
    ->modalHeading('Delete Post')
    ->modalDescription(fn (Post $record) => "Are you sure you want to delete \"{$record->title}\"? This action cannot be undone.")
    ->modalSubmitActionLabel('Yes, Delete'),
```

Hasil:
- Modal konfirmasi menampilkan judul post yang akan dihapus
- Pesan warning yang jelas tentang aksi yang tidak bisa dibatalkan
- Tombol submit dengan label custom "Yes, Delete"

---

### Step E: Bulk Actions — Publish & Unpublish

**Import yang dibutuhkan:**
```php
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
```

**Tambahkan di BulkActionGroup:**
```php
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

BulkAction::make('unpublish')
    ->label('Unpublish Selected')
    ->icon(Heroicon::OutlinedXMark)
    ->color('danger')
    ->requiresConfirmation()
    ->action(function (Collection $records) {
        $records->each(fn (Post $record) => $record->update(['published' => false]));
        Notification::make()
            ->title($records->count() . ' posts unpublished successfully!')
            ->warning()
            ->send();
    })
    ->deselectRecordsAfterCompletion(),
```

Hasil:
- Centang beberapa record → muncul dropdown "Bulk Actions"
- Pilih "Publish Selected" → semua post yang dipilih di-publish
- Pilih "Unpublish Selected" → semua post yang dipilih di-unpublish
- Notifikasi menampilkan jumlah record yang berhasil diubah
- Checkbox otomatis di-deselect setelah aksi selesai

---

### Step F: Table Actions pada ProductsTable

**File:** `app/Filament/Resources/Products/Tables/ProductsTable.php`

**Record Actions dengan Custom Toggle:**
```php
Action::make('toggleActive')
    ->label(fn (Product $record) => $record->is_active ? 'Deactivate' : 'Activate')
    ->icon(fn (Product $record) => $record->is_active
        ? Heroicon::OutlinedXCircle
        : Heroicon::OutlinedCheckCircle)
    ->color(fn (Product $record) => $record->is_active ? 'danger' : 'success')
    ->requiresConfirmation()
    ->action(function (Product $record) {
        $record->update(['is_active' => !$record->is_active]);
        $status = $record->is_active ? 'activated' : 'deactivated';
        Notification::make()
            ->title("Product {$status} successfully!")
            ->success()
            ->send();
    }),
```

**Bulk Actions — Activate, Deactivate, Feature:**
```php
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
```

---

### Step G: Perbandingan Jenis Actions

| Aspek | Record Action | Bulk Action | Custom Action |
|-------|--------------|-------------|---------------|
| **Scope** | Satu record | Banyak record | Satu record |
| **Posisi** | Kolom aksi di setiap baris | Dropdown di toolbar | Di dalam ActionGroup |
| **Parameter** | `$record` (model) | `Collection $records` | `$record` (model) |
| **Contoh** | View, Edit, Delete | Bulk Delete, Bulk Publish | Clone, Toggle Status |
| **Konfirmasi** | Optional | Recommended | Recommended |

---

## 📸 Screenshot Evidence

- ![ActionGroup Dropdown]
<img width="1320" height="539" alt="image" src="https://github.com/user-attachments/assets/7ae64aee-31d3-4079-8995-43faa18708ac" />

- ![Clone Confirmation Modal]
<img width="1324" height="554" alt="image" src="https://github.com/user-attachments/assets/ff5781c4-b34b-4fd0-b973-8f159a485f68" />

- ![Delete Confirmation Modal]
<img width="1324" height="639" alt="image" src="https://github.com/user-attachments/assets/2fe4907a-053d-4b65-9458-f7f6b016af08" />

- ![Bulk Actions]
<img width="1339" height="582" alt="image" src="https://github.com/user-attachments/assets/620b0df4-e800-4bad-b717-4eeb0a401729" />

- ![Success Notification]
<img width="1344" height="628" alt="image" src="https://github.com/user-attachments/assets/5e7ee5cc-f8fb-4cd4-b698-ced19144f8ad" />


---

## 🏋️ Step H: Latihan Praktikum

1. ActionGroup pada PostsTable (View, Edit, Clone, Delete) ✅
2. Custom Clone Action dengan konfirmasi modal ✅
3. Delete Action dengan modal description dinamis ✅
4. Bulk Publish & Unpublish dengan notifikasi ✅
5. `deselectRecordsAfterCompletion()` pada bulk actions ✅
6. ActionGroup pada ProductsTable (View, Edit, Toggle Active, Toggle Featured, Delete) ✅
7. Bulk Activate, Deactivate, Feature pada ProductsTable ✅
8. Screenshot: ActionGroup, Clone Modal, Delete Modal, Bulk Actions, Notification ✅

---

## 📝 Kode Akhir PostsTable.php

```php
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
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
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
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Post')
                        ->modalDescription(fn (Post $record) => "Are you sure you want to delete \"{$record->title}\"? This action cannot be undone.")
                        ->modalSubmitActionLabel('Yes, Delete'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Posts')
                        ->modalDescription('Are you sure you want to delete all selected posts? This action cannot be undone.'),
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
```

---

## 📝 Kode Akhir ProductsTable.php

```php
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
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info'),
                    EditAction::make()
                        ->color('warning'),
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
                    Action::make('toggleFeatured')
                        ->label(fn (Product $record) => $record->is_featured ? 'Unfeature' : 'Feature')
                        ->icon(Heroicon::OutlinedStar)
                        ->color(fn (Product $record) => $record->is_featured ? 'gray' : 'warning')
                        ->action(function (Product $record) {
                            $record->update(['is_featured' => !$record->is_featured]);
                            $status = $record->is_featured ? 'featured' : 'unfeatured';
                            Notification::make()
                                ->title("Product marked as {$status}!")
                                ->success()
                                ->send();
                        }),
                    DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Product')
                        ->modalDescription(fn (Product $record) => "Are you sure you want to delete \"{$record->name}\"? This action cannot be undone.")
                        ->modalSubmitActionLabel('Yes, Delete'),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Delete Selected Products')
                        ->modalDescription('Are you sure you want to delete all selected products?'),
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
```

---

## 🔍 Analisis & Diskusi (Bagian I)

### 1. Mengapa menggunakan ActionGroup?

- **Menghemat ruang** — Tanpa ActionGroup, setiap aksi ditampilkan sebagai tombol terpisah di setiap baris. Dengan banyak aksi (View, Edit, Clone, Delete), tabel menjadi terlalu lebar.
- **ActionGroup** mengelompokkan semua aksi dalam dropdown menu (ikon ⋮), sehingga tabel tetap bersih dan rapi.
- **Konsistensi UX** — Pengguna sudah terbiasa dengan pola dropdown aksi di aplikasi web modern.

### 2. Apa fungsi requiresConfirmation()?

- **Mencegah kesalahan** — Untuk aksi destruktif (Delete, Unpublish), modal konfirmasi memastikan pengguna benar-benar ingin melakukan aksi tersebut.
- **Kustomisasi modal:**
  - `modalHeading()` — Judul modal
  - `modalDescription()` — Pesan deskripsi (bisa dinamis dengan closure)
  - `modalSubmitActionLabel()` — Label tombol konfirmasi
- **Dynamic content** — Menggunakan closure `fn (Post $record) =>` untuk menampilkan data record di modal (misal: judul post yang akan dihapus).

### 3. Apa perbedaan Record Action dan Bulk Action?

| Aspek | Record Action | Bulk Action |
|-------|--------------|-------------|
| **Jumlah record** | 1 record | Banyak record |
| **Parameter** | `Post $record` (single model) | `Collection $records` (kumpulan model) |
| **Trigger** | Klik di baris tabel | Centang beberapa record → pilih aksi |
| **Use case** | View, Edit, Delete, Clone | Bulk Publish, Bulk Delete, Bulk Activate |
| **Deselect** | Tidak perlu | `->deselectRecordsAfterCompletion()` |

### 4. Mengapa perlu deselectRecordsAfterCompletion()?

- Setelah bulk action selesai, checkbox yang tercentang tetap tercentang secara default.
- `deselectRecordsAfterCompletion()` otomatis menghapus semua centang setelah aksi selesai.
- Ini mencegah pengguna tidak sengaja menjalankan bulk action lagi pada record yang sama.
- **Best practice:** Selalu gunakan method ini pada bulk actions.

### 5. Bagaimana Notification bekerja di Filament?

```php
Notification::make()
    ->title('Post cloned successfully!')  // Judul notifikasi
    ->success()                             // Tipe: success (hijau)
    ->send();                               // Kirim notifikasi
```

- **Tipe notifikasi:** `->success()` (hijau), `->warning()` (kuning), `->danger()` (merah), `->info()` (biru)
- Notifikasi muncul di pojok kanan atas halaman
- Otomatis hilang setelah beberapa detik
- Memberikan feedback visual yang jelas kepada pengguna setelah aksi berhasil

---
