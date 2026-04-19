# 🎓 Praktikum Pemrograman Web Lanjut - Week 7

## 📚 Implementasi Wizard Form (Multi Step Form) di Filament

Proyek Laravel ini merupakan hasil praktikum **Pertemuan 7** mata kuliah **Pemrograman Web Lanjut** yang mengimplementasikan Wizard Form untuk input data produk dengan 3 steps dalam aplikasi e-commerce.

---


### 1. Clone dan Setup Project

```bash
# Pastikan Anda di folder Week-7/PraktikumPWL
cd "e:\Teknik Informatika\Semester 4\Pemrograman Web Lanjut\Week-7\PraktikumPWL"

# Install dependencies
composer install
npm install

# Generate APP_KEY jika belum ada
php artisan key:generate

# Publish file storage
php artisan storage:link
```

### 2. Konfigurasi Database

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=filament2026
DB_USERNAME=root
DB_PASSWORD=
```

**Pastikan MySQL sudah running!** (XAMPP > Start MySQL)

### 3. Jalankan Migration

```bash
php artisan migrate
```

### 4. Jalankan Development Server

```bash
php artisan serve
```

Akses di: **http://localhost:8000/admin/products**

---

## 📊 Struktur Database

Tabel **products** dengan field:

| Field | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint | Primary Key (auto increment) |
| name | string | Nama Produk (required) |
| sku | string | Kode Produk (required, unique) |
| description | text | Deskripsi Produk (required) |
| price | integer | Harga Produk IDR (required, min 1) |
| stock | integer | Jumlah Stok (required, min 0) |
| image | string | Path Gambar (optional) |
| is_active | boolean | Status Aktif (default: true) |
| is_featured | boolean | Status Featured (default: false) |
| created_at | timestamp | Waktu Dibuat |
| updated_at | timestamp | Waktu Diupdate |

---

## 🏗️ Arsitektur Project

### Struktur Folder

```
app/
├── Models/
│   └── Product.php                      ← Model Produk
└── Filament/
    └── Resources/
        └── Products/
            ├── ProductResource.php       ← Resource utama
            ├── Pages/
            │   ├── CreateProduct.php    ← Form create
            │   ├── EditProduct.php      ← Form edit
            │   ├── ListProducts.php     ← List tabel
            │   └── ViewProduct.php      ← View detail
            ├── Schemas/
            │   ├── ProductForm.php      ← Wizard Form
            │   └── ProductInfolist.php  ← Info list
            └── Tables/
                └── ProductsTable.php    ← Tabel columns

database/
└── migrations/
    └── 2026_04_19_045514_create_products_table.php
```

---

## 🧙 Wizard Form Details

### Step 1: Product Info 🏷️

- **Input Fields:**
  - Nama Produk (TextInput, required)
  - SKU (TextInput, required, unique)
  - Deskripsi (MarkdownEditor, required)

- **Layout:** Nama & SKU dalam 2 kolom, Deskripsi full width
- **Icon:** information-circle
- **Validasi:**
  - Field wajib diisi
  - SKU harus unik
  - Deskripsi boleh kosong

### Step 2: Pricing & Stock 💰

- **Input Fields:**
  - Harga (TextInput numeric, required, min 1)
  - Stok (TextInput numeric, required, min 0)

- **Layout:** Harga & Stok dalam 2 kolom
- **Icon:** tag
- **Validasi:**
  - Harga minimal 1 IDR
  - Stok minimal 0

### Step 3: Media & Status 📷

- **Input Fields:**
  - Gambar Produk (FileUpload, optional)
  - Is Active (Checkbox)
  - Is Featured (Checkbox)

- **Layout:** Checkbox dalam 2 kolom
- **Icon:** photo
- **Validasi:**
  - File hanya accept image
  - Size maksimal 5MB

### Tombol Wizard

```
[🏷️ Product Info] → [💰 Pricing & Stock] → [📷 Media & Status]
```

- Navigation buttons: **Back** dan **Next**
- Submit button: **Simpan Produk** (pada step terakhir)

---

## 📋 Tabel Products

### Kolom yang Ditampilkan

| Kolom | Tipe | Fitur |
|-------|------|-------|
| Nama Produk | TextColumn | Searchable, Sortable |
| SKU | TextColumn | Searchable, Sortable |
| Harga | TextColumn | Format IDR, Sortable |
| Stok | TextColumn | Sortable |
| Gambar | ImageColumn | Disk: public |
| Status | BadgeColumn | Aktif (hijau), Nonaktif (merah) |

### Action Buttons

- 👁️ **View** - Lihat detail produk
- ✏️ **Edit** - Edit produk
- 🗑️ **Delete** - Hapus produk (bulk action)

---

## 🧪 Testing Guide

### Test Case 1: Create Product

```
1. Klik "New product"
2. Step 1 - Isi:
   - Nama: "Buku PHP"
   - SKU: "B-001"
   - Deskripsi: "Buku panduan PHP terlengkap"
3. Klik "Next"
4. Step 2 - Isi:
   - Harga: 150000
   - Stok: 50
5. Klik "Next"
6. Step 3 - Isi:
   - Upload gambar (opsional)
   - Centang "Is Active"
7. Klik "Simpan Produk"
```

### Test Case 2: Validasi

```
❌ Test Skip Required Field:
   - Coba klik Next tanpa isi Nama
   - Expected: Error message

❌ Test SKU Unique:
   - Input SKU yang sudah ada
   - Expected: Error message "SKU sudah digunakan"

❌ Test Harga Minimal:
   - Input harga 0
   - Expected: Error message
```

### Test Case 3: Update Product

```
1. Klik Edit pada produk di tabel
2. Ubah beberapa field
3. Klik "Simpan Produk"
4. Verifikasi perubahan di tabel
```

---


## 🛠️ Commands Penting

```bash
# Jalankan development server
php artisan serve

# Jalankan migration
php artisan migrate

# Rollback migration (jika perlu)
php artisan migrate:rollback

# Reset database
php artisan migrate:reset

# Fresh migration (reset + migrate)
php artisan migrate:fresh

# Buat symlink untuk storage public
php artisan storage:link

# Clear cache
php artisan cache:clear
php artisan view:clear
```

---

## 📂 File Paths

| File | Path |
|------|------|
| Model Product | `app/Models/Product.php` |
| Migration | `database/migrations/2026_04_19_045514_create_products_table.php` |
| ProductResource | `app/Filament/Resources/Products/ProductResource.php` |
| Wizard Form | `app/Filament/Resources/Products/Schemas/ProductForm.php` |
| Tabel Products | `app/Filament/Resources/Products/Tables/ProductsTable.php` |
| Create Page | `app/Filament/Resources/Products/Pages/CreateProduct.php` |
| Edit Page | `app/Filament/Resources/Products/Pages/EditProduct.php` |
| List Page | `app/Filament/Resources/Products/Pages/ListProducts.php` |
| Laporan | `PRAKTIKUM_LAPORAN.md` |

---

## 🎓 Key Concepts

### Wizard Form Benefits ✨

- ✅ Mengurangi cognitive load pengguna
- ✅ Membagi form kompleks jadi step logis
- ✅ Meningkatkan UX dan completion rate
- ✅ Mobile-friendly
- ✅ Better error handling per step

### Validasi per Step 🔍

- Validasi automatic pada setiap step
- User bisa kembali ke step sebelumnya
- Error message jelas dan helpful

### Multi-Column Layout 📐

- Menghemat space di form
- Layout lebih menarik
- Label & input terorganisir

---

## 🔗 Resources & References

- [Filament Documentation](https://filamentphp.com)
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Validation](https://laravel.com/docs/validation)
- [Filament Wizard](https://filamentphp.com/docs/forms/layout/wizard)

---

## ⚙️ Requirements

- PHP 8.1+
- Laravel 11
- Filament 3
- MySQL 5.7+
- Node.js 16+

---

## 📝 Notes

- Semua gambar produk disimpan di `/storage/app/public/products/`
- Gunakan `php artisan storage:link` untuk membuat symlink
- SKU harus unik, tidak boleh duplikat
- Default `is_active` = true untuk produk baru
- Database harus ada sebelum jalankan migration

---

# 🎓 Pertemuan 8 - Implementasi Info List (View Page) di Filament

## 📚 Tujuan Pembelajaran

Setelah mengikuti praktikum ini, mahasiswa mampu:

✅ Memahami konsep Info List pada Filament  
✅ Mengubah tampilan View Page dari form menjadi display informasi  
✅ Menggunakan TextEntry, ImageEntry, dan IconEntry  
✅ Menggunakan Badge, Color, Icon, dan Format Date  
✅ Mendesain halaman detail (show page) yang lebih profesional  

---

## 🎯 Latar Belakang

Pada pertemuan sebelumnya, kita telah membuat Wizard Form pada module Product. Namun ketika tombol View diklik, halaman detail masih menampilkan form input (kurang tepat untuk tampilan informasi).

**Solusinya:** Menggunakan Info List agar data ditampilkan dalam bentuk informasi (read-only display).

---

## 🔧 Implementasi

### File yang Dimodifikasi

📄 **ProductInfolist.php**
```
app/Filament/Resources/Products/Schemas/ProductInfolist.php
```

### Komponen Info List yang Digunakan

| Komponen | Fungsi | Analogi |
|----------|--------|---------|
| **TextEntry** | Menampilkan teks | TextColumn (di Tabel) |
| **ImageEntry** | Menampilkan gambar | ImageColumn (di Tabel) |
| **IconEntry** | Menampilkan icon boolean | IconColumn (di Tabel) |
| **badge()** | Menampilkan dalam bentuk badge | Badge styling |
| **color()** | Memberi warna | Color styling |
| **weight()** | Bold text | Font weight |
| **icon()** | Menambahkan icon | Icon indicator |
| **date()** | Format tanggal | Date formatting |

---

## 📋 Struktur Info List

### Section 1: Product Info 🏷️

```php
Section::make('Product Info')
    ->description('Informasi dasar produk')
    ->schema([
        TextEntry::make('name')
            ->label('Product Name')
            ->weight('bold')
            ->color('primary'),
        TextEntry::make('id')
            ->label('Product ID'),
        TextEntry::make('sku')
            ->label('Product SKU')
            ->badge()
            ->color('success'),
        TextEntry::make('description')
            ->label('Product Description'),
        TextEntry::make('created_at')
            ->label('Product Creation Date')
            ->date('d M Y')
            ->color('info'),
    ])
    ->columnSpanFull(),
```

**Fitur:**
- Nama produk dengan text bold dan warna primary
- SKU ditampilkan sebagai badge dengan warna success
- Tanggal dibuat dengan format "01 Mar 2026"

---

### Section 2: Pricing & Stock 💰

```php
Section::make('Pricing & Stock')
    ->description('Harga dan Stok Produk')
    ->schema([
        TextEntry::make('price')
            ->label('Product Price')
            ->weight('bold')
            ->color('primary')
            ->icon('heroicon-o-currency-dollar')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
        TextEntry::make('stock')
            ->label('Product Stock')
            ->weight('bold')
            ->color('primary')
            ->icon('heroicon-o-cube')
            ->formatStateUsing(fn ($state) => $state . ' unit'),
    ])
    ->columnSpanFull(),
```

**Fitur:**
- Harga diformat menjadi "Rp X.XXX.XXX" (Rupiah)
- Stock ditampilkan dengan "X unit"
- Icon currency-dollar untuk harga
- Icon cube untuk stock

---

### Section 3: Image and Status 📷

```php
Section::make('Image and Status')
    ->description('Gambar dan Status Produk')
    ->schema([
        ImageEntry::make('image')
            ->label('Product Image')
            ->disk('public'),
        IconEntry::make('is_active')
            ->label('Is Active')
            ->boolean(),
        IconEntry::make('is_featured')
            ->label('Is Featured')
            ->boolean(),
    ])
    ->columnSpanFull(),
```

**Fitur:**
- Menampilkan gambar dari storage public
- Is Active: ✓ (hijau) jika true, ✗ (merah) jika false
- Is Featured: ✓ (hijau) jika true, ✗ (merah) jika false

---

## 🧪 Testing Guide - Pertemuan 8

### Test Case 1: View Product Detail

```
1. Go to: http://localhost:8000/admin/products
2. Klik icon 👁️ pada salah satu produk
3. Verifikasi setiap section muncul:
   ✅ Section Product Info
   ✅ Section Pricing & Stock
   ✅ Section Image and Status
4. Cek formatting:
   ✅ Harga format Rp
   ✅ Stock format unit
   ✅ Tanggal format "d M Y"
   ✅ Boolean icon ✓ dan ✗
5. Pastikan Edit button tersedia
```

### Test Case 2: Validasi Icon Boolean

```
Product dengan is_active = true:
   ✅ Muncul icon check (✓) warna hijau

Product dengan is_active = false:
   ❌ Muncul icon cross (✗) warna merah
```

### Test Case 3: Validasi Image Display

```
1. Product dengan gambar:
   ✅ Gambar ditampilkan di Image and Status section
   
2. Product tanpa gambar:
   ✅ Placeholder atau kosong
```

---

## 🎨 Hasil yang Diharapkan

### Sebelum (View Page dengan Form)
- Tampilan form input
- Field editable
- Kurang informatif

### Sesudah (View Page dengan Info List)
- Tampilan display profesional
- Read-only (tidak bisa diubah)
- Lebih rapi & terstruktur
- Badge & icon untuk visual yang lebih baik

---

## 📸 Screenshot Evidence

### Screenshot 1: Section Product Info
*Menampilkan Name (bold), ID, SKU (badge), Description, Creation Date*

[Tambahkan screenshot di sini]

### Screenshot 2: Section Pricing & Stock
*Menampilkan Price (format Rp) dan Stock (format unit) dengan icon*

[Tambahkan screenshot di sini]

### Screenshot 3: Section Image and Status
*Menampilkan Image, Is Active (boolean icon), Is Featured (boolean icon)*

[Tambahkan screenshot di sini]

---

## 💾 Database Check

Pastikan produk test memiliki data lengkap:

```sql
SELECT id, name, sku, price, stock, image, is_active, is_featured, created_at 
FROM products 
LIMIT 2;
```

Seharusnya ada minimal 2 produk dengan:
- Nama dan SKU terisi
- Harga > 0
- Stock > 0
- Gambar (opsional)
- is_active & is_featured (boolean)

---

## 🔍 Key Points

### TextEntry vs TextColumn

| TextEntry | TextColumn |
|-----------|-----------|
| Digunakan di Info List | Digunakan di Tabel |
| Untuk display detail | Untuk display list |
| Read-only | Read-only |
| Bisa complex formatting | Formatting sederhana |

### Icon Entry Boolean

- `->boolean()` otomatis menampilkan check/cross icon
- Warna otomatis: hijau (true), merah (false)
- Sangat user-friendly untuk status

### formatStateUsing()

Digunakan untuk formatting kompleks:
```php
->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
```

---

## ✅ Checklist Completion

- [ ] Edit ProductInfolist.php
- [ ] Tambah Section Product Info
- [ ] Tambah Section Pricing & Stock
- [ ] Tambah Section Image and Status
- [ ] Format harga menjadi Rp
- [ ] Format stock dengan unit
- [ ] Add boolean icons untuk status
- [ ] Test view product detail
- [ ] Screenshot setiap section
- [ ] Update README dengan findings

---

## 🔗 File References

| Jenis | Path |
|--------|------|
| Info List Schema | `app/Filament/Resources/Products/Schemas/ProductInfolist.php` |
| Product Model | `app/Models/Product.php` |
| Product Resource | `app/Filament/Resources/Products/ProductResource.php` |
| View Page | `app/Filament/Resources/Products/Pages/ViewProduct.php` |

---

## 🎓 Konsep yang Dipelajari

✅ **Info List Basics** - Understand InfoList concept  
✅ **TextEntry Component** - Display text data  
✅ **ImageEntry Component** - Display images  
✅ **IconEntry Component** - Display boolean as icons  
✅ **Data Formatting** - Format price, date, number  
✅ **Styling** - Badge, color, weight, icon  
✅ **Professional UI** - Create polished detail page  

---

## 🛠️ Commands untuk Pertemuan 8

```bash
# Jalankan development server
php artisan serve

# Clear cache jika ada perubahan
php artisan cache:clear
php artisan view:clear

# Test database connection
php artisan tinker
>>> Product::first();
```

---

# 🎓 Pertemuan 9 - Tabs in Details Deep Dive

## 📚 Tujuan Pembelajaran

Setelah mengikuti praktikum ini, mahasiswa mampu:

1. Menggunakan komponen Tabs pada Info List
2. Mengelompokkan informasi detail ke dalam beberapa tab
3. Menambahkan icon dan badge pada tab
4. Mengubah orientasi tab (horizontal & vertical)
5. Mendesain halaman View agar lebih ringkas dan user-friendly

**Framework yang digunakan:** Filament

---

## 🎯 Latar Belakang (Bagian A)

Pada pertemuan sebelumnya, kita telah menggunakan **Info List** dengan **Section** untuk menampilkan detail Product. Namun jika data cukup banyak, pengguna harus scroll panjang ke bawah.

**Solusi:** Gunakan **Tabs** agar informasi dibagi menjadi beberapa kategori dan dapat diakses dengan klik.

Contoh pembagian:
- Tab 1 → Product Info
- Tab 2 → Pricing & Stock
- Tab 3 → Media & Status

---

## 🧩 Konsep Tabs di Info List (Bagian B)

Tabs digunakan untuk:
- Membagi informasi ke dalam beberapa halaman kecil
- Mengurangi scrolling panjang
- Meningkatkan user experience

---

## 🔧 Langkah Praktikum

### Step C: Mengubah Section Menjadi Tabs

**File yang dimodifikasi:**
```
app/Filament/Resources/Products/Schemas/ProductInfolist.php
```

**Sebelum (Section-based):**

```php
<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Info')
                    ->description('Informasi dasar produk')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Product Name')
                            ->weight('bold')
                            ->color('primary'),
                        TextEntry::make('sku')
                            ->label('SKU')
                            ->badge()
                            ->color('success'),
                        TextEntry::make('description')
                            ->label('Description'),
                    ])
                    ->columnSpanFull(),

                Section::make('Pricing & Stock')
                    ->description('Harga dan Stok Produk')
                    ->schema([
                        TextEntry::make('price')
                            ->label('Price')
                            ->icon('heroicon-o-currency-dollar'),
                        TextEntry::make('stock')
                            ->label('Stock'),
                    ])
                    ->columnSpanFull(),

                Section::make('Media & Status')
                    ->description('Gambar dan Status Produk')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Product Image')
                            ->disk('public'),
                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        IconEntry::make('is_featured')
                            ->label('Featured')
                            ->boolean(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
```


---

### Step D: Implementasi Tabs

**Sesudah (Tabs-based):**

```php
<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product Tabs')
                    ->tabs([
                        Tabs\Tab::make('Product Info')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('description')
                                    ->label('Description'),
                            ])
                            ->columnSpanFull(),

                        Tabs\Tab::make('Pricing & Stock')
                            ->icon('heroicon-o-currency-dollar')
                            ->badge('10')
                            ->badgeColor('info')
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('stock')
                                    ->label('Stock'),
                            ]),

                        Tabs\Tab::make('Media & Status')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Product Image')
                                    ->disk('public'),
                                IconEntry::make('is_active')
                                    ->label('Active')
                                    ->boolean(),
                                IconEntry::make('is_featured')
                                    ->label('Featured')
                                    ->boolean(),
                            ]),
                    ]),
            ]);
    }
}
```

**Penjelasan per Tab:**

| Tab | Icon | Badge | Komponen |
|-----|------|-------|----------|
| Product Info | `heroicon-o-academic-cap` | - | TextEntry (name, sku, description) |
| Pricing & Stock | `heroicon-o-currency-dollar` | `10` (info) | TextEntry (price, stock) |
| Media & Status | `heroicon-o-photo` | - | ImageEntry, IconEntry (is_active, is_featured) |

---

### Step E: Tampilan Tabs Horizontal

<img width="502" height="475" alt="Screenshot 2026-04-19 132503" src="https://github.com/user-attachments/assets/9f01a895-1ea6-4594-bfef-578af6504642" />



**Cara mendapatkan tampilan horizontal:** Jangan tambahkan method `->vertical()`, secara default Tabs akan tampil horizontal.

---

### Step F: Mengubah Tabs Menjadi Vertical

Tambahkan method `->vertical()` pada komponen Tabs:

```php
Tabs::make('Product Tabs')
    ->vertical()
    ->tabs([
        // ...
    ])
```

<img width="542" height="410" alt="Screenshot 2026-04-19 132640" src="https://github.com/user-attachments/assets/669ab4a4-f068-47c6-8195-07c58922e35c" />



---

### Step G: Fitur Tambahan Tabs

| Method | Fungsi |
|--------|--------|
| `icon()` | Menambahkan icon pada tab |
| `badge()` | Menambahkan badge angka |
| `badgeColor()` | Mengubah warna badge |
| `columnSpanFull()` | Membuat full width |
| `vertical()` | Mengubah orientasi tab |

---

### Step H: Perbandingan Section vs Tabs

| Aspek | Section | Tabs |
|-------|---------|------|
| Navigasi | Scroll panjang | Klik tab |
| Tampilan | Semua tampil sekaligus | Terpisah per kategori |
| Ringkasan | Kurang ringkas | Lebih profesional |
| UX | Pengguna harus scroll | Pengguna klik untuk pindah |
| Cocok untuk | Data sedikit | Data banyak & berkategori |

---

### Step J: Latihan Praktikum

#### 1. Badge Dinamis Berdasarkan Jumlah Stok

Mengganti badge statis `'10'` menjadi dinamis berdasarkan data stok produk:

```php
->badge(fn ($record) => $record?->stock ?? 0)
```

> Badge sekarang menampilkan jumlah stok aktual dari database, bukan angka statis.

#### 2. Warna Badge Berbeda

Menambahkan warna badge yang berubah secara dinamis berdasarkan jumlah stok:

```php
->badgeColor(fn ($record) =>
    match(true) {
        ($record?->stock ?? 0) >= 10 => 'success',  // Hijau - stok aman
        ($record?->stock ?? 0) >= 5 => 'warning',    // Kuning - stok menipis
        default => 'danger',                          // Merah - stok kritis
    }
)
```

| Kondisi Stok | Warna Badge | Status |
|-------------|-------------|--------|
| ≥ 10 | success (hijau) | Stok aman |
| ≥ 5 | warning (kuning) | Stok menipis |
| < 5 | danger (merah) | Stok kritis |

#### 3. Ubah Tampilan Menjadi Vertical

Sudah diimplementasikan dengan `->vertical()` pada Tabs.

#### 4. Tambahkan Icon Berbeda pada Tiap Tab

| Tab | Icon | Deskripsi |
|-----|------|-----------|
| Product Info | `heroicon-o-academic-cap` | Icon topi akademik untuk info produk |
| Pricing & Stock | `heroicon-o-currency-dollar` | Icon dolar untuk harga & stok |
| Media & Status | `heroicon-o-photo` | Icon foto untuk media & status |

#### 5. Screenshot

- [x] Tabs horizontal
- [x] Tabs vertical
- [x] Tab dengan badge

<img width="570" height="270" alt="Screenshot 2026-04-19 132746" src="https://github.com/user-attachments/assets/45d04b16-5499-42f4-a363-ded941b5bd64" />


---

## 📝 Kode Akhir ProductInfolist.php

```php
<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Product Tabs')
                    ->vertical()
                    ->tabs([
                        Tabs\Tab::make('Product Info')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Product Name')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('sku')
                                    ->label('SKU')
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('description')
                                    ->label('Description'),
                            ])
                            ->columnSpanFull(),

                        Tabs\Tab::make('Pricing & Stock')
                            ->icon('heroicon-o-currency-dollar')
                            ->badge(fn ($record) => $record?->stock ?? 0)
                            ->badgeColor(fn ($record) =>
                                match(true) {
                                    ($record?->stock ?? 0) >= 10 => 'success',
                                    ($record?->stock ?? 0) >= 5 => 'warning',
                                    default => 'danger',
                                }
                            )
                            ->schema([
                                TextEntry::make('price')
                                    ->label('Price')
                                    ->icon('heroicon-o-currency-dollar'),
                                TextEntry::make('stock')
                                    ->label('Stock'),
                            ]),

                        Tabs\Tab::make('Media & Status')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Product Image')
                                    ->disk('public'),
                                IconEntry::make('is_active')
                                    ->label('Active')
                                    ->boolean(),
                                IconEntry::make('is_featured')
                                    ->label('Featured')
                                    ->boolean(),
                            ]),
                    ]),
            ]);
    }
}
```

---



## ✅ Checklist Completion - Pertemuan 9

- [x] Mengganti Section menjadi Tabs
- [x] Membuat 3 Tab berbeda (Product Info, Pricing & Stock, Media & Status)
- [x] Menambahkan icon pada Tab
- [x] Menambahkan badge
- [x] Mengubah orientasi ke vertical
- [x] Latihan: Badge dinamis berdasarkan stok
- [x] Latihan: Warna badge berbeda (success/warning/danger)
- [x] Latihan: Icon berbeda pada tiap tab
- [x] Screenshot: Tabs horizontal
- [x] Screenshot: Tabs vertical
- [x] Screenshot: Tab dengan badge
- [x] Jawaban analisis (Bagian K)

---

## 📂 File References - Pertemuan 9

| Jenis | Path |
|--------|------|
| Info List Schema | `app/Filament/Resources/Products/Schemas/ProductInfolist.php` |
| Product Resource | `app/Filament/Resources/Products/ProductResource.php` |
| View Page | `app/Filament/Resources/Products/Pages/ViewProduct.php` |
| Product Model | `app/Models/Product.php` |

---

## 🎓 Kesimpulan (Bagian L)

Pada pertemuan ini mahasiswa telah mempelajari:

- Penggunaan Tabs pada Info List sebagai pengganti Section
- Mengatur tampilan View Page menjadi lebih interaktif dengan navigasi tab
- Menambahkan icon & badge pada tab untuk UX yang lebih baik
- Mengubah orientasi tampilan dari horizontal ke vertical
- Implementasi badge dinamis yang berubah sesuai data record
- Implementasi badgeColor dinamis dengan match expression

---

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
