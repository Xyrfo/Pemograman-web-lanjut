
# 🎓 Pertemuan 10 - Implementasi Sorting (Ascending & Descending) pada Table Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |


## 📚 Capaian Pembelajaran

1. Menambahkan fitur sorting kolom pada tabel Filament
2. Menggunakan method `sortable()`
3. Menerapkan sorting pada kolom relasi
4. Menerapkan sorting pada kolom tanggal
5. Mengatur default sorting tabel

---

## 🎯 Latar Belakang (Bagian A)

Pada modul Post, kita sudah memiliki tabel dengan kolom: Image, Title, Slug, Category, Created At. Namun saat data bertambah banyak, pengguna membutuhkan fitur sorting. Filament menyediakan fitur sorting yang sangat sederhana.

## 🧩 Konsep Sorting di Filament (Bagian B)

Pada Laravel biasa, sorting membutuhkan query manual, kondisi orderBy, dan parameter request. Namun di Filament cukup dengan satu method: `->sortable()`

---

## 🔧 Langkah Praktikum

### Step C: Sorting pada Kolom Title

**File:** `app/Filament/Resources/Posts/Tables/PostsTable.php`

```php
TextColumn::make('title')
    ->sortable(),
```

Hasil: Klik 1 → Ascending (A–Z), Klik 2 → Descending (Z–A)

### Step D: Sorting pada Kolom Slug

```php
TextColumn::make('slug')
    ->sortable(),
```

### Step E: Sorting pada Relasi (Category)

```php
TextColumn::make('category.name')
    ->sortable(),
```

Filament otomatis menangani join relasi.

### Step F: Sorting pada Kolom Tanggal

```php
TextColumn::make('created_at')
    ->label('Created At')
    ->dateTime()
    ->sortable(),
```

### Step G: Mengatur Default Sorting

```php
return $table
    ->defaultSort('title', 'asc')
    ->columns([...]);
```

### Step H: Opsi Default Sort

| Opsi | Fungsi |
|------|--------|
| `asc` | Urut naik (A–Z / 0–9) |
| `desc` | Urut turun (Z–A / 9–0) |

### Step I: Ringkasan Method Sorting

| Method | Fungsi |
|--------|--------|
| `sortable()` | Mengaktifkan sorting kolom |
| `defaultSort()` | Mengatur sorting default |
| `dateTime()` | Format tanggal |
| `label()` | Mengubah nama kolom |

---

## 📸 Screenshot Evidence

- ![Sorting Title Asc](screenshots/sorting-title-asc.png)
- ![Sorting Title Desc](screenshots/sorting-title-desc.png)
- ![Sorting Date Desc](screenshots/sorting-date-desc.png)

---

## 🏋️ Step K: Latihan Praktikum

1. Sorting aktif pada semua kolom teks (title, slug, category.name, created_at)
2. Default sorting: `->defaultSort('created_at', 'desc')` — post terbaru tampil di atas
3. Uji ascending & descending dengan klik header kolom

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
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->sortable(),
                TextColumn::make('slug')
                    ->sortable(),
                TextColumn::make('category.name')
                    ->sortable(),
                ColorColumn::make('color'),
                ImageColumn::make('image')->disk('public'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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

## 🔍 Analisis & Diskusi (Bagian L)

### 1. Mengapa sorting penting pada admin panel?

- **Data besar** — Admin panel menangani ratusan/ribuan record, tanpa sorting pengguna harus scroll manual
- **Akses cepat** — Sorting memungkinkan pengguna langsung menemukan data yang dicari (misal: post terbaru)
- **Pengambilan keputusan** — Membantu admin melihat pola data (misal: produk stok terendah perlu restock)
- **Manajemen prioritas** — Data paling relevan bisa ditampilkan di atas
- **UX profesional** — Sorting adalah fitur dasar admin panel, sama seperti search dan pagination

### 2. Apa perbedaan sortable biasa dengan defaultSort()?

| Aspek | `sortable()` | `defaultSort()` |
|-------|-------------|-----------------|
| Fungsi | Mengaktifkan sorting pada kolom (user klik header) | Mengatur urutan default saat tabel pertama kali dimuat |
| Pengguna | User yang memilih sorting | Otomatis oleh sistem |
| Penempatan | Pada setiap kolom | Pada level table |
| Interaksi | Muncul icon sort di header kolom | Tidak ada interaksi user |

`sortable()` memberikan kontrol sorting kepada user, `defaultSort()` mengatur tampilan awal tabel. Keduanya saling melengkapi.

### 3. Mengapa relasi tetap bisa di-sort?

Filament secara otomatis menangani query join ke tabel relasi. Saat kita menulis `TextColumn::make('category.name')->sortable()`, Filament:
1. Mendeteksi `category` sebagai relasi dan `name` sebagai field di tabel relasi
2. Menambahkan `JOIN` ke query Eloquent secara otomatis
3. Mengarahkan `orderBy` ke field `name` pada tabel `categories`
4. Mengoptimasi query join agar performa tetap baik

Di Laravel manual kita harus menulis query join dan orderBy sendiri, Filament menangani semuanya otomatis.

### 4. Kapan kita menggunakan desc sebagai default?

`desc` sebaiknya digunakan saat:
- **Data berbasis waktu** — Post terbaru, order terbaru, log terbaru (contoh: `->defaultSort('created_at', 'desc')`)
- **Data prioritas tinggi** — Alert tertinggi, error terkritis, stok terendah
- **Data yang sering diupdate** — Record yang baru diupdate tampil di atas untuk verifikasi
- **Aktivitas terbaru** — Login terakhir, transaksi terakhir, komentar terbaru
- **Nilai tertinggi** — Penjualan tertinggi, revenue terbesar

Kebalikannya, `asc` cocok untuk data master seperti daftar nama (A–Z), daftar kategori yang bersifat referensi.

---



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
