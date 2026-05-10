# 🎓 Pertemuan 12 - Implementasi Toggle Column pada Table Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

## 📚 Capaian Pembelajaran

1. Memahami konsep Toggle Column pada Filament
2. Menggunakan `ToggleColumn` untuk field boolean (published, is_active, is_featured)
3. Menggunakan method `toggleable()` untuk show/hide kolom pada tabel
4. Menggunakan `toggledHiddenByDefault` untuk menyembunyikan kolom secara default
5. Menerapkan Toggle Column pada resource Post dan Product

---

## 🎯 Latar Belakang (Bagian A)

Pada pertemuan sebelumnya kita telah menambahkan Search & Filter pada tabel Post. Namun dalam pengelolaan data, sering kali:
- Admin perlu mengubah status boolean (published/aktif) langsung dari tabel tanpa membuka halaman edit
- Tabel memiliki banyak kolom sehingga pengguna perlu kemampuan untuk menyembunyikan kolom yang tidak diperlukan

Filament menyediakan dua fitur untuk mengatasi ini:
- **`ToggleColumn`** — Menampilkan toggle switch pada tabel untuk mengubah nilai boolean secara langsung
- **`toggleable()`** — Memungkinkan pengguna menampilkan/menyembunyikan kolom melalui column picker

---

## 🔧 Langkah Praktikum

### Step B: Menambahkan ToggleColumn pada PostsTable

**File:** `app/Filament/Resources/Posts/Tables/PostsTable.php`

**Import ToggleColumn:**
```php
use Filament\Tables\Columns\ToggleColumn;
```

**Tambahkan ToggleColumn untuk field `published`:**
```php
ToggleColumn::make('published')
    ->label('Published')
    ->sortable()
    ->toggleable(),
```

Hasil:
- Toggle switch muncul pada kolom Published
- Admin bisa langsung mengubah status published tanpa membuka halaman edit
- Perubahan tersimpan otomatis ke database

---

### Step C: Menambahkan toggleable() pada Kolom PostsTable

Tambahkan `->toggleable()` pada setiap kolom agar pengguna bisa show/hide kolom:

```php
TextColumn::make('title')
    ->searchable()
    ->sortable()
    ->toggleable(),
```

Untuk kolom yang jarang digunakan, sembunyikan secara default:
```php
TextColumn::make('slug')
    ->searchable()
    ->sortable()
    ->toggleable(isToggledHiddenByDefault: true),

ColorColumn::make('color')
    ->toggleable(isToggledHiddenByDefault: true),
```

Hasil:
- Ikon column picker muncul di toolbar tabel
- Klik ikon tersebut untuk menampilkan dropdown daftar kolom
- Kolom slug dan color tersembunyi secara default
- Pengguna dapat mencentang/uncentang kolom sesuai kebutuhan

---

### Step D: Menambahkan ToggleColumn pada ProductsTable

**File:** `app/Filament/Resources/Products/Tables/ProductsTable.php`

**Ganti `BadgeColumn` dengan `ToggleColumn` untuk `is_active`:**
```php
use Filament\Tables\Columns\ToggleColumn;

ToggleColumn::make('is_active')
    ->label('Status Aktif')
    ->sortable()
    ->toggleable(),
```

**Tambahkan ToggleColumn untuk `is_featured`:**
```php
ToggleColumn::make('is_featured')
    ->label('Featured')
    ->sortable()
    ->toggleable(),
```

Hasil:
- Toggle switch untuk Status Aktif dan Featured pada tabel Products
- Admin bisa mengubah status langsung dari tabel
- Tidak perlu membuka halaman edit untuk mengubah status

---

### Step E: Menambahkan toggleable() pada Semua Kolom ProductsTable

Tambahkan `->toggleable()` pada setiap kolom:

```php
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
```

Hasil:
- Column picker tersedia di tabel Products
- SKU tersembunyi secara default (karena jarang dibutuhkan saat browsing)

---

### Step F: Perbandingan ToggleColumn vs toggleable()

| Aspek | `ToggleColumn` | `toggleable()` |
|-------|---------------|----------------|
| **Fungsi** | Menampilkan toggle switch untuk mengubah nilai boolean | Memungkinkan show/hide kolom dari column picker |
| **Tipe data** | Hanya untuk field boolean | Bisa digunakan pada semua tipe kolom |
| **Interaksi** | Klik toggle → data langsung berubah di DB | Klik column picker → centang/uncentang kolom |
| **Tujuan** | Mengubah data tanpa membuka form edit | Menyederhanakan tampilan tabel |
| **Contoh field** | published, is_active, is_featured | title, slug, created_at, dll |

---

## 📸 Screenshot Evidence

- ![Toggle Published]
<img width="1337" height="560" alt="image" src="https://github.com/user-attachments/assets/e2527f5b-a281-459a-9381-3f579d1c76c1" />

- ![Column Picker Post]
<img width="1342" height="663" alt="image" src="https://github.com/user-attachments/assets/055fd012-3f78-487c-9b24-2aa94f88c02d" />

- ![Toggle Product]
<img width="1320" height="655" alt="image" src="https://github.com/user-attachments/assets/b062acd2-28e8-4235-84e3-ce819621881f" />

- ![Column Picker Product]
<img width="1318" height="639" alt="image" src="https://github.com/user-attachments/assets/b7cd6f06-19b2-4564-ae13-0a3df8250455" />


---

## 🏋️ Step G: Latihan Praktikum

1. ToggleColumn untuk `published` pada PostsTable ✅
2. `toggleable()` pada semua kolom PostsTable ✅
3. `toggledHiddenByDefault` pada slug dan color ✅
4. ToggleColumn untuk `is_active` dan `is_featured` pada ProductsTable ✅
5. `toggleable()` pada semua kolom ProductsTable ✅
6. `toggledHiddenByDefault` pada SKU di ProductsTable ✅
7. Screenshot: Toggle Published, Column Picker Post, Toggle Product, Column Picker Product ✅

---

## 📝 Kode Akhir PostsTable.php

```php
<?php

namespace App\Filament\Resources\Posts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Table;

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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

---

## 🔍 Analisis & Diskusi (Bagian H)

### 1. Apa itu ToggleColumn dan kapan penggunaannya?

- **ToggleColumn** adalah tipe kolom khusus di Filament yang menampilkan toggle switch pada tabel
- Toggle switch ini terhubung langsung ke field boolean pada database
- Ketika admin mengklik toggle, nilai langsung berubah di database tanpa perlu membuka halaman edit
- **Cocok digunakan untuk:** field boolean yang sering diubah seperti `published`, `is_active`, `is_featured`
- **Keuntungan:** mempercepat workflow admin karena tidak perlu navigasi ke halaman edit hanya untuk mengubah satu field boolean

### 2. Apa perbedaan ToggleColumn dan toggleable()?

- **`ToggleColumn`** adalah **tipe kolom** — menampilkan toggle switch untuk mengubah nilai boolean di database. Ini mengubah DATA.
- **`toggleable()`** adalah **method** yang bisa ditambahkan ke kolom apapun — memungkinkan pengguna menyembunyikan/menampilkan kolom dari tampilan tabel. Ini mengubah TAMPILAN, bukan data.
- Keduanya bisa dikombinasikan: `ToggleColumn::make('published')->toggleable()` artinya kolom published menampilkan toggle switch DAN bisa disembunyikan dari tabel.

### 3. Mengapa perlu toggledHiddenByDefault?

- **Tabel dengan banyak kolom** bisa membuat tampilan terlalu padat dan sulit dibaca
- `isToggledHiddenByDefault: true` menyembunyikan kolom tertentu secara default agar tampilan lebih bersih
- Kolom yang disembunyikan tetap bisa ditampilkan oleh pengguna melalui column picker
- **Contoh penggunaan:** kolom `slug` jarang dibutuhkan saat browsing data, jadi disembunyikan secara default. Begitu juga `color` dan `sku`.
- **Best practice:** Kolom penting (title, category, status) tetap ditampilkan; kolom teknis/jarang digunakan disembunyikan

### 4. Bagaimana ToggleColumn menyimpan perubahan ke database?

- ToggleColumn secara otomatis mengirim request AJAX ke server saat toggle diklik
- Filament menangani update ke database melalui model Eloquent
- Field yang digunakan harus ada di `$fillable` pada model (contoh: `'published'` di model Post)
- Field harus bertipe boolean di database (`$table->boolean('published')`)
- Tidak perlu menulis controller atau route tambahan — Filament menangani semuanya secara otomatis

---
