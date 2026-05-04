# 🎓 Pertemuan 11 - Implementasi Search & Filter pada Table Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

## 📚 Capaian Pembelajaran

1. Menambahkan fitur Search (Pencarian) pada tabel
2. Menggunakan method `searchable()`
3. Membuat filter berdasarkan tanggal (Date Filter)
4. Membuat filter berdasarkan relasi (Select Filter)
5. Menambahkan query custom pada filter
6. Menggabungkan fitur Search dan Filter secara bersamaan

---

## 🎯 Latar Belakang (Bagian A)

Pada pertemuan sebelumnya kita telah menambahkan sorting pada tabel Post. Namun dalam sistem yang memiliki banyak data, pengguna juga membutuhkan:
- Pencarian berdasarkan teks (title, slug, kategori)
- Filter berdasarkan tanggal
- Filter berdasarkan kategori

Filament menyediakan fitur tersebut dengan sangat sederhana.

---

## 🔧 Langkah Praktikum

### Step B: Menambahkan Search pada Kolom

**File:** `app/Filament/Resources/Posts/Tables/PostsTable.php`

**Search pada Title:**
```php
TextColumn::make('title')
    ->searchable(),
```

**Search pada Slug:**
```php
TextColumn::make('slug')
    ->searchable(),
```

**Search pada Relasi Category:**
```php
TextColumn::make('category.name')
    ->searchable(),
```

Hasil:
- Search bar muncul otomatis di atas tabel
- Bisa mencari berdasarkan Title, Slug, dan Category
- Hasil tampil secara real-time

---

### Step C: Membuat Filter Berdasarkan Tanggal

Search cocok untuk teks, tetapi tidak efektif untuk tanggal. Solusinya: gunakan Filter.

Tambahkan import:
```php
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
```

Tambahkan di method `filters()`:
```php
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
```

Cara Menguji:
1. Klik icon filter di tabel
2. Pilih tanggal
3. Klik Apply
4. Data akan terfilter sesuai tanggal

---

### Step D: Membuat Filter Berdasarkan Relasi (Kategori)

Tambahkan import:
```php
use Filament\Tables\Filters\SelectFilter;
```

Tambahkan:
```php
SelectFilter::make('category_id')
    ->label('Select Category')
    ->relationship('category', 'name')
    ->preload(),
```

Hasil:
- Dropdown kategori muncul di filter
- Data otomatis difilter berdasarkan kategori yang dipilih

---

### Step E: Perbandingan Search vs Filter

| Aspek | Search | Filter |
|-------|--------|--------|
| Digunakan untuk | Teks | Kondisi spesifik |
| Cara kerja | Real-time saat mengetik | Berdasarkan form input |
| Cocok untuk | title, slug | tanggal & relasi |
| Interaksi | Ketik di search bar | Klik icon filter → pilih → Apply |

---

## 📸 Screenshot Evidence

- ![Search Title](screenshots/search-title.png)
- ![Filter Tanggal](screenshots/filter-tanggal.png)
- ![Filter Kategori](screenshots/filter-kategori.png)

---

## 🏋️ Step G: Latihan Praktikum

1. Search aktif pada 3 kolom (title, slug, category.name) ✅
2. Filter tanggal Created At dengan DatePicker ✅
3. Filter kategori menggunakan SelectFilter ✅
4. Uji kombinasi Search + Filter — ketik di search bar lalu aktifkan filter, keduanya bekerja bersamaan
5. Screenshot: Search Title, Filter Tanggal, Filter Kategori ✅

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
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                ColorColumn::make('color'),
                ImageColumn::make('image')->disk('public'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
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

## 🔍 Analisis & Diskusi (Bagian H)

### 1. Mengapa search tidak cocok untuk filter tanggal?

- **Format tidak konsisten** — Tanggal bisa ditulis dalam berbagai format (2026-04-29, 29 Apr 2026, 29/04/2026), sehingga pencarian teks bisa gagal menemukan data
- **Tidak mendukung range** — Search hanya mencari exact match, tidak bisa mencari "antara tanggal X dan Y"
- **User experience buruk** — Pengguna harus ingat dan mengetik format tanggal yang tepat, berbeda dengan DatePicker yang memudahkan pemilihan tanggal
- **Performa** — Search melakukan `LIKE %keyword%` pada string, sedangkan filter tanggal menggunakan `whereDate()` yang lebih efisien karena memanfaatkan index database
- **Tidak presisi** — Search bisa mengembalikan hasil yang tidak diinginkan (misal: mencari "2026" mengembalikan semua data tahun 2026, bukan tanggal spesifik)

### 2. Apa fungsi relationship() pada SelectFilter?

Fungsi `->relationship('category', 'name')` pada SelectFilter:

- **Menghubungkan ke model relasi** — Parameter pertama (`'category'`) merujuk pada nama relasi yang didefinisikan di model Post (method `category()`)
- **Menentukan field yang ditampilkan** — Parameter kedua (`'name'`) menentukan field dari model Category yang akan ditampilkan sebagai opsi di dropdown
- **Auto-populate dropdown** — Filament otomatis mengambil semua data dari tabel categories dan menampilkan field `name` sebagai pilihan
- **Auto-filter query** — Saat user memilih kategori, Filament otomatis menambahkan `WHERE category_id = X` ke query tanpa perlu menulis query manual

### 3. Mengapa kita perlu whereDate() pada query filter?

- **Perbandingan tanggal saja** — `whereDate('created_at', $date)` membandingkan hanya bagian tanggal (Y-m-d) dari kolom `created_at`, mengabaikan bagian waktu (H:i:s). Jika menggunakan `where('created_at', $date)`, perbandingan gagal karena `created_at` berisi datetime (misal: `2026-04-29 14:30:00`) sedangkan `$date` hanya berisi date (`2026-04-29`)
- **Akurasi filtering** — Tanpa `whereDate()`, data dengan tanggal yang sama tetapi waktu berbeda tidak akan terfilter
- **Database compatibility** — `whereDate()` menghasilkan query SQL yang menggunakan fungsi `DATE()` pada kolom, kompatibel di berbagai database
- **User intent** — Ketika user memilih tanggal di DatePicker, mereka ingin melihat semua data pada tanggal tersebut, terlepas dari jam berapa data dibuat

### 4. Apa perbedaan searchable() dan filters()?

| Aspek | `searchable()` | `filters()` |
|-------|---------------|-------------|
| **Fungsi** | Pencarian teks real-time pada kolom | Filter berdasarkan kondisi spesifik |
| **UI** | Search bar di atas tabel | Icon filter → form popup → Apply |
| **Cara kerja** | `LIKE %keyword%` pada kolom | Custom query berdasarkan input form |
| **Tipe data cocok** | String/teks (title, slug, nama) | Tanggal, relasi, boolean, enum |
| **Real-time** | Ya, hasil langsung muncul saat mengetik | Tidak, perlu klik Apply dulu |
| **Penempatan** | Pada setiap kolom | Pada level table |
| **Kombinasi** | Bisa dikombinasikan dengan filter | Bisa dikombinasikan dengan search |

**Kesimpulan:** `searchable()` untuk pencarian teks bebas, `filters()` untuk penyaringan terstruktur. Keduanya bisa digunakan bersamaan untuk pengalaman pencarian yang lengkap.

---
