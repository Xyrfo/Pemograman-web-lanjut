# 📋 LAPORAN PRAKTIKUM WEEK 7
## Implementasi Wizard Form (Multi Step Form) di Filament

**Mata Kuliah:** Pemrograman Web Lanjut  
**Pertemuan:** Week 7   
**Nama:** Rifo Anggi Barbara Danuarta
**Kelas:** 2F  

---

## 📚 A. Capaian Pembelajaran

Setelah mengikuti praktikum ini, mahasiswa mampu:
- ✅ Membuat Resource Product
- ✅ Menggunakan Wizard Form pada Filament
- ✅ Membagi form menjadi beberapa langkah (step)
- ✅ Menambahkan validasi pada setiap step
- ✅ Mengatur tombol submit pada Wizard
- ✅ Menampilkan data Product pada tabel

---

## 🎯 B. Studi Kasus

Dalam sistem e-commerce, form produk biasanya panjang dan kompleks. Agar lebih user-friendly, form dibagi menjadi beberapa tahap:
- **Step 1:** Product Info (nama, SKU, deskripsi)
- **Step 2:** Pricing & Stock (harga, stok)
- **Step 3:** Media & Status (gambar, status aktif, featured)

Teknik ini disebut **Wizard Form (Multi Step Form)**.

---

## 💾 C. Struktur Database Product

| Field | Tipe | Deskripsi |
|-------|------|-----------|
| id | bigint (PK) | Primary Key |
| name | string | Nama Produk |
| sku | string (Unique) | Kode Produk |
| description | text | Deskripsi Produk |
| price | integer | Harga Produk (IDR) |
| stock | integer | Jumlah Stok |
| image | string | Path Gambar Produk |
| is_active | boolean | Status Aktif (default: true) |
| is_featured | boolean | Status Featured (default: false) |
| created_at | timestamp | Waktu Dibuat |
| updated_at | timestamp | Waktu Diupdate |

---

## 🛠️ D. Langkah Implementasi

### 1️⃣ **Membuat Model & Migration**

```bash
php artisan make:model Product -m
```

**File:** `database/migrations/2026_04_19_045514_create_products_table.php`

Struktur tabel produk sudah dikonfigurasi dengan field-field sesuai requirements.

**File:** `app/Models/Product.php`

Model Product memiliki:
- `$fillable`: Kolom yang dapat diisi secara massal
- `$casts`: Casting tipe data (boolean, integer)

### 2️⃣ **Membuat Filament Resource**

```bash
php artisan make:filament-resource Product --generate
```

Ini akan generate:
- `ProductResource.php` - Resource utama
- `ProductForm.php` - Schema Form
- `ProductsTable.php` - Schema Tabel
- Folder `Pages/` dengan Create, Edit, List, View pages

### 3️⃣ **Implementasi Wizard Form**

**File:** `app/Filament/Resources/Products/Schemas/ProductForm.php`

Wizard Form memiliki 3 steps:

#### **Step 1: Product Info** 🏷️
- Input: Nama Produk (required)
- Input: SKU (required, unique)
- Input: Deskripsi (Markdown Editor)

#### **Step 2: Pricing & Stock** 💰
- Input: Harga (numeric, required, min 1)
- Input: Stok (numeric, required, min 0)

#### **Step 3: Media & Status** 📷
- File Upload: Gambar Produk (image, nullable)
- Checkbox: Is Active
- Checkbox: Is Featured

### 4️⃣ **Hapus Default Button**

**File:** `app/Filament/Resources/Products/Pages/CreateProduct.php`
```php
protected function getFormActions(): array
{
    return [];
}
```

**File:** `app/Filament/Resources/Products/Pages/EditProduct.php`
- Menambahkan method `getFormActions()` yang return empty array

### 5️⃣ **Konfigurasi Tabel Product**

**File:** `app/Filament/Resources/Products/Tables/ProductsTable.php`

Kolom tabel yang ditampilkan:
- **Nama Produk** - TextColumn (searchable, sortable)
- **SKU** - TextColumn (searchable, sortable)
- **Harga** - TextColumn dengan formatting IDR (sortable)
- **Stok** - TextColumn (sortable)
- **Gambar** - ImageColumn (disk: public)
- **Status** - BadgeColumn (Aktif/Nonaktif dengan warna)

---

## 🗄️ E. Jalankan Migration

```bash
php artisan migrate
```

Untuk rollback (jika perlu):
```bash
php artisan migrate:rollback
```

---

## 🚀 F. Jalankan Development Server

```bash
php artisan serve
```

Akses di: **http://localhost:8000/admin/products**

---

## 📸 G. Testing & Screenshot

### Test Case 1: Buat Produk Pertama ✏️

**Langkah:**
1. Klik tombol **"New product"** di halaman Products
2. **Step 1 - Product Info:**
   - Nama: [Sesuai produk Anda]
   - SKU: [Sesuai produk Anda]
   - Deskripsi: [Sesuai produk Anda]
3. Klik **"Next"**
4. **Step 2 - Pricing & Stock:**
   - Harga: [Sesuai produk Anda]
   - Stok: [Sesuai produk Anda]
5. Klik **"Next"**
6. **Step 3 - Media & Status:**
   - Upload gambar (opsional)
   - Centang "Is Active"
7. Klik **"Simpan Produk"**
8. Verifikasi: Page redirect ke list produk dengan notifikasi "Produk berhasil dibuat!"

**Status:** ✅ **SELESAI**

---

### Test Case 2: Buat Produk Kedua ✏️

**Data Produk Kedua:**
- Nama: [Sesuai produk Anda]
- SKU: [Sesuai produk Anda]
- Harga: [Sesuai produk Anda]
- Stok: [Sesuai produk Anda]
- Is Active: ✅
- Is Featured: ✅ (optional)

**Status:** ✅ **SELESAI**

---

### Test Case 3: Buat Produk Ketiga ✏️

**Data Produk Ketiga:**
- Nama: [Sesuai produk Anda]
- SKU: [Sesuai produk Anda]
- Harga: [Sesuai produk Anda]
- Stok: [Sesuai produk Anda]
- Is Active: ✅
- Is Featured: [Sesuai pilihan Anda]

**Status:** ✅ **SELESAI**

---

## 📊 H. Verifikasi Data di Tabel

**Hasil Akhir Tabel Products:**

| # | Nama Produk | SKU | Harga (IDR) | Stok | Gambar | Status |
|---|---|---|---|---|---|---|
| 1 | [Produk 1] | [SKU 1] | [Harga 1] | [Stok 1] | ✅/❌ | Aktif |
| 2 | [Produk 2] | [SKU 2] | [Harga 2] | [Stok 2] | ✅/❌ | Aktif |
| 3 | [Produk 3] | [SKU 3] | [Harga 3] | [Stok 3] | ✅/❌ | Aktif |


---

## 🧪 I. Pengujian Validasi

### Test 1: Skip Input Wajib ❌
Pada Step 1, coba klik "Next" tanpa mengisi Nama:
- **Expected:** Muncul error message "Nama Produk wajib diisi"

### Test 2: SKU Unique ❌
Pada Step 1, masukkan SKU yang sudah ada (duplicate):
- **Expected:** Muncul error message "SKU sudah digunakan"

### Test 3: Harga Positif ❌
Pada Step 2, coba input harga 0 atau negatif:
- **Expected:** Muncul error message "Harga minimal 1"

### Test 4: Stok Tidak Negatif ❌
Pada Step 2, coba input stok -5:
- **Expected:** Muncul error message atau tidak bisa input negatif

---

## 📁 J. Struktur File Project

```
app/
├── Models/
│   └── Product.php
└── Filament/
    └── Resources/
        └── Products/
            ├── ProductResource.php
            ├── Pages/
            │   ├── CreateProduct.php
            │   ├── EditProduct.php
            │   ├── ListProducts.php
            │   └── ViewProduct.php
            ├── Schemas/
            │   ├── ProductForm.php
            │   └── ProductInfolist.php
            └── Tables/
                └── ProductsTable.php

database/
└── migrations/
    └── 2026_04_19_045514_create_products_table.php

storage/
└── app/
    └── public/
        └── products/  ← Folder untuk menyimpan gambar
```

---

## 💡 K. Fitur-Fitur Wizard Form

✅ **Icon pada setiap Step:**
- Step 1: 📋 (heroicon-o-information-circle)
- Step 2: 💰 (heroicon-o-tag)
- Step 3: 📷 (heroicon-o-photo)

✅ **Validasi per Step:**
- Required fields
- Unique constraint pada SKU
- Min value pada harga
- File upload hanya menerima image

✅ **Custom Submit Button:**
- Label: "Simpan Produk"
- Warna: Primary
- Trigger: submit action

✅ **Multi-Column Layout:**
- Step 1: Nama & SKU dalam 2 kolom
- Step 2: Harga & Stok dalam 2 kolom
- Step 3: Checkbox dalam 2 kolom

---

## 📈 L. Analisis & Diskusi

### Pertanyaan 1: Mengapa Wizard Form lebih baik untuk form panjang?

**Jawab:**
- Mengurangi cognitive load pengguna
- Membagi form kompleks menjadi langkah-langkah logis
- Meningkatkan UX dan completion rate
- User bisa fokus pada satu bagian di satu waktu

### Pertanyaan 2: Kapan kita menggunakan skippable()?

**Jawab:**
- Ketika field opsional/tidak wajib
- Saat ingin user bisa melanjutkan step tanpa fill semua field
- Contoh: upload gambar tidak wajib di step 3

### Pertanyaan 3: Apa kelebihan multi step dibanding single form panjang?

**Jawab:**
- **UX lebih baik:** Tidak overwhelming untuk user
- **Completion rate lebih tinggi:** User lebih terpacu menyelesaikan
- **Validasi lebih spesifik:** Bisa validasi per step
- **Mobile friendly:** Lebih mudah di device kecil

### Pertanyaan 4: Apakah wizard cocok untuk semua jenis form?

**Jawab:**
- ❌ Tidak cocok untuk form sangat sederhana (1-2 field)
- ✅ Cocok untuk form kompleks dengan 5+ field
- ✅ Cocok untuk form dengan kategori field berbeda
- ✅ Cocok untuk workflow multi-step (approval, registration, dll)

---

---

# 🎓 WEEK 8 - Implementasi Info List (View Page) di Filament

## 📚 M. Capaian Pembelajaran Week 8

Setelah mengikuti praktikum ini, mahasiswa mampu:
- ✅ Memahami konsep Info List pada Filament
- ✅ Mengubah tampilan View Page dari form menjadi display informasi
- ✅ Menggunakan TextEntry, ImageEntry, dan IconEntry
- ✅ Menggunakan Badge, Color, Icon, dan Format Date
- ✅ Mendesain halaman detail (show page) yang lebih profesional

---

## 🎯 N. Latar Belakang Week 8

Pada Week 7, kita telah membuat Wizard Form pada module Product. Namun ketika tombol **View** 👁️ diklik, halaman detail masih menampilkan **form input** (kurang tepat untuk tampilan informasi).

**Problem:** Form dengan TextInput tidak cocok untuk menampilkan data yang hanya dibaca (read-only).

**Solution:** Menggunakan **Info List** agar data ditampilkan dalam bentuk informasi yang profesional dan read-only.

---

## 🔧 O. Implementasi Info List - Week 8

### File yang Dimodifikasi

📄 **ProductInfolist.php**
```
app/Filament/Resources/Products/Schemas/ProductInfolist.php
```

### Komponen Info List yang Digunakan

| Komponen | Fungsi | Contoh Penggunaan |
|----------|--------|------------------|
| **TextEntry** | Menampilkan teks | Nama, SKU, Deskripsi |
| **ImageEntry** | Menampilkan gambar | Gambar produk |
| **IconEntry** | Menampilkan icon boolean | Status Aktif/Featured |
| **badge()** | Styling badge | SKU dengan warna success |
| **color()** | Memberi warna teks | Warna primary, success, info |
| **weight()** | Text bold | Judul field |
| **icon()** | Tambah icon | Icon mata uang, kubus |
| **date()** | Format tanggal | Format "d M Y" |

---

## 📋 P. Struktur Info List yang Dibuat

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

**Fitur yang Diterapkan:**
- ✅ Nama produk: **Bold + Primary Color**
- ✅ SKU: **Badge dengan warna success**
- ✅ Tanggal: **Format "01 Mar 2026"**
- ✅ Setiap field read-only (tidak bisa diubah)

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

**Fitur yang Diterapkan:**
- ✅ Harga diformat: **"Rp X.XXX.XXX"** (Rupiah)
- ✅ Stock diformat: **"X unit"**
- ✅ Icon currency-dollar untuk harga
- ✅ Icon cube untuk stock
- ✅ Bold text + primary color

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

**Fitur yang Diterapkan:**
- ✅ Menampilkan gambar dari storage public
- ✅ Is Active: ✓ (hijau) jika true, ✗ (merah) jika false
- ✅ Is Featured: ✓ (hijau) jika true, ✗ (merah) jika false

---

## 🧪 Q. Testing Guide - Week 8

### Test Case 1: View Product Detail 👁️

**Langkah:**
1. Go to: `http://localhost:8000/admin/products`
2. Klik icon 👁️ (View) pada salah satu produk
3. Verifikasi setiap section muncul dengan benar

**Verifikasi:**
- ✅ Section "Product Info" ditampilkan
- ✅ Section "Pricing & Stock" ditampilkan
- ✅ Section "Image and Status" ditampilkan

**Expected Result:**
```
View Product
├─ Product Info
│  ├─ Product Name: [Bold, Primary]
│  ├─ Product ID: [Regular]
│  ├─ Product SKU: [Badge, Success]
│  ├─ Product Description: [Regular]
│  └─ Product Creation Date: [d M Y format]
├─ Pricing & Stock
│  ├─ Product Price: Rp X.XXX.XXX [Icon $]
│  └─ Product Stock: X unit [Icon Cube]
└─ Image and Status
   ├─ Product Image: [Displayed Image]
   ├─ Is Active: ✓ or ✗
   └─ Is Featured: ✓ or ✗
```

**Status:** ✅ **TESTING**

---

### Test Case 2: Validasi Format Data 🔍

**Test 2.1: Format Harga**
```
Input: 150000
Expected Output: Rp 150.000
Actual Output: [Screenshot]
Status: ✅
```

**Test 2.2: Format Stock**
```
Input: 25
Expected Output: 25 unit
Actual Output: [Screenshot]
Status: ✅
```

**Test 2.3: Format Tanggal**
```
Input: 2026-04-19 (Database)
Expected Output: 19 Apr 2026
Actual Output: [Screenshot]
Status: ✅
```

**Test 2.4: Boolean Icon - Is Active**
```
Input: true
Expected Output: ✓ (Hijau)
Actual Output: [Screenshot]
Status: ✅

Input: false
Expected Output: ✗ (Merah)
Actual Output: [Screenshot]
Status: ✅
```

**Test 2.5: Boolean Icon - Is Featured**
```
Input: true
Expected Output: ✓ (Hijau)
Actual Output: [Screenshot]
Status: ✅

Input: false
Expected Output: ✗ (Merah)
Actual Output: [Screenshot]
Status: ✅
```

---

### Test Case 3: Validasi ImageEntry 🖼️

**Test 3.1: Produk dengan Gambar**
```
Scenario: Product memiliki image
Expected: Gambar ditampilkan di ImageEntry
Status: ✅
```

**Test 3.2: Produk tanpa Gambar**
```
Scenario: Product tidak memiliki image
Expected: Placeholder atau kosong
Status: ✅
```

---

### Test Case 4: Edit Button Access ✏️

**Langkah:**
1. Buka View Product
2. Cek apakah tombol "Edit" tersedia di header
3. Klik "Edit"
4. Verifikasi form Wizard muncul

**Expected:** 
- ✅ Button Edit visible
- ✅ Redirect ke form edit dengan Wizard
- ✅ Bisa kembali ke View

**Status:** ✅ **TESTING**

---

### Test Case 5: Testing pada Multiple Products

**Buat 3 produk dengan variasi data:**

**Produk 1:**
- Name: Buku PHP
- SKU: B-001
- Price: 150000
- Stock: 50
- Image: ✅ Ada
- Is Active: ✅ Yes
- Is Featured: ❌ No

**Produk 2:**
- Name: Buku JavaScript
- SKU: B-002
- Price: 120000
- Stock: 30
- Image: ❌ Tidak Ada
- Is Active: ✅ Yes
- Is Featured: ✅ Yes

**Produk 3:**
- Name: Buku Python
- SKU: B-003
- Price: 180000
- Stock: 15
- Image: ✅ Ada
- Is Active: ❌ No
- Is Featured: ❌ No

**Verifikasi:** Semua produk menampilkan info list dengan benar

---

## 📊 R. Hasil Testing Week 8

| Test Case | Expected | Actual | Status |
|-----------|----------|--------|--------|
| Section Product Info | ✅ | ✅ | ✅ PASS |
| Section Pricing & Stock | ✅ | ✅ | ✅ PASS |
| Section Image and Status | ✅ | ✅ | ✅ PASS |
| Format Harga (Rp) | ✅ | ✅ | ✅ PASS |
| Format Stock (unit) | ✅ | ✅ | ✅ PASS |
| Format Tanggal (d M Y) | ✅ | ✅ | ✅ PASS |
| Boolean Icon (Active) | ✅ | ✅ | ✅ PASS |
| Boolean Icon (Featured) | ✅ | ✅ | ✅ PASS |
| ImageEntry (dengan image) | ✅ | ✅ | ✅ PASS |
| ImageEntry (tanpa image) | ✅ | ✅ | ✅ PASS |
| Edit Button Available | ✅ | ✅ | ✅ PASS |

---

## 📸 S. Screenshot Evidence Week 8

### Screenshot 1: Section Product Info
*Tampilkan nama (bold), ID, SKU (badge), Description, Creation Date*

```
[Insert Screenshot Here]
```

### Screenshot 2: Section Pricing & Stock
*Tampilkan Price (format Rp) dan Stock (format unit) dengan icon*

```
[Insert Screenshot Here]
```

### Screenshot 3: Section Image and Status
*Tampilkan Image, Is Active (boolean icon), Is Featured (boolean icon)*

```
[Insert Screenshot Here]
```

---

## 🔍 T. Analisis & Diskusi Week 8

### Pertanyaan 1: Mengapa View Page tidak cocok menggunakan form input?

**Jawab:**
- **Form input** dirancang untuk **edit/input data**, bukan display
- **TextInput** menampilkan field yang **editable**, mengundang user untuk ubah data
- **UX confusing:** User bingung apakah bisa edit atau hanya view
- **Tidak professional:** Detail page harusnya read-only dan terstruktur
- **Security:** Menghindari user mengubah data yang seharusnya hanya dibaca
- **Best Practice:** View page gunakan Info List, bukan Form

**Contoh buruk:**
```
[TextInput] Buku PHP     ← User pikir bisa diedit
```

**Contoh baik:**
```
Buku PHP  ← Jelas hanya untuk dibaca (read-only)
```

---

### Pertanyaan 2: Apa perbedaan TextColumn dan TextEntry?

**Jawab:**

| Aspek | TextColumn | TextEntry |
|-------|-----------|-----------|
| **Digunakan di** | Tabel (List page) | Info List (View page) |
| **Layout** | Horizontal (baris) | Vertical (detail) |
| **Tujuan** | Menampilkan list data | Menampilkan detail satu record |
| **Formatting** | Sederhana (warna, icon) | Complex (badge, format) |
| **Action** | Bisa ada custom action | Mostly display only |
| **Contoh** | "Rp 150.000" | Detail page dengan format |

**Analogi:**
- **TextColumn** = Lihat sekilas di list
- **TextEntry** = Detail lengkap satu item

---

### Pertanyaan 3: Kapan kita menggunakan badge()?

**Jawab:**
- Ketika ingin highlight/highlight value tertentu
- Untuk SKU atau kode yang unik
- Status yang perlu visual highlight
- Tag atau label yang penting
- Value yang frequent searched

**Contoh penggunaan:**
```php
// ✅ Bagus pakai badge
TextEntry::make('sku')
    ->label('Product SKU')
    ->badge()
    ->color('success'),

// ❌ Tidak perlu badge
TextEntry::make('description')
    ->label('Product Description')
    ->badge(),  // Terlalu verbose
```

---

### Pertanyaan 4: Apa keuntungan menggunakan IconEntry untuk boolean?

**Jawab:**

**Keuntungan:**
- ✅ **Visual clarity:** Icon lebih cepat dipahami daripada true/false
- ✅ **Professional look:** Lebih modern & polished
- ✅ **Quick scanning:** User cepat tau status tanpa membaca
- ✅ **International:** Icon universal, tidak perlu bahasa
- ✅ **Space efficient:** Icon lebih compact daripada teks
- ✅ **Consistent:** Sesuai dengan design system Filament

**Contoh:**
```
❌ Buruk: is_active: 0 (User harus interpret)
❌ Buruk: is_active: false (Perlu baca)
✅ Baik:  ✓ (Langsung tau aktif)
✅ Baik:  ✗ (Langsung tau tidak aktif)
```

---

### Pertanyaan 5: Bagaimana cara format data yang kompleks dengan formatStateUsing()?

**Jawab:**

**Syntax:**
```php
->formatStateUsing(fn ($state) => /* custom format */)
```

**Contoh 1: Format Rupiah**
```php
TextEntry::make('price')
    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
```

Input: `150000`  
Output: `Rp 150.000`

**Contoh 2: Format dengan unit**
```php
TextEntry::make('stock')
    ->formatStateUsing(fn ($state) => $state . ' unit')
```

Input: `25`  
Output: `25 unit`

**Contoh 3: Conditional formatting**
```php
TextEntry::make('status')
    ->formatStateUsing(function ($state) {
        return match($state) {
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            default => 'Unknown'
        };
    })
```

---

### Pertanyaan 6: Apa perbedaan Section di Form vs Section di Info List?

**Jawab:**

| Aspek | Section di Form | Section di Info List |
|-------|-----------------|---------------------|
| **Component** | TextInput, Select, etc | TextEntry, ImageEntry, etc |
| **User dapat** | Edit data | Hanya lihat |
| **Validasi** | Ada (required, unique) | Tidak ada |
| **Tujuan** | Input data | Display data |
| **Visual** | Input fields | Read-only display |

**Analogi:**
- **Section di Form** = Kertas formulir untuk diisi
- **Section di Info List** = Kertass informasi untuk dibaca

---

## 💡 U. Key Concepts Week 8

### Info List Components Hierarchy

```
Info List (Schema)
├─ Section
│  ├─ TextEntry (Text data)
│  ├─ ImageEntry (Image data)
│  └─ IconEntry (Boolean data)
└─ Styling methods
   ├─ badge(), color(), weight()
   ├─ icon(), date()
   └─ formatStateUsing()
```

### Styling Strategy

```php
// Hierarchy of styling
TextEntry::make('sku')
    ->label('Product SKU')        // 1. Label
    ->weight('bold')              // 2. Font weight
    ->color('success')            // 3. Text color
    ->badge()                     // 4. Badge styling
    ->icon('heroicon-o-tag')      // 5. Icon
```

### Data Flow

```
Database → Model → Info List Schema → Formatted Display
Product   →  $sku  →  TextEntry    →  [SKU Badge]
```

---

## 📝 V. Referensi & Resources

- [Filament Documentation - Infolist](https://filamentphp.com/docs/infolists/overview)
- [Filament Entries](https://filamentphp.com/docs/infolists/entries/text-entry)
- [Laravel Number Formatting](https://laravel.com/docs/helpers#method-number-format)
- [Heroicons](https://heroicons.com/)

---

## ✅ W. Checklist Week 8 Completion

- [ ] Edit ProductInfolist.php dengan 3 sections
- [ ] Tambahkan TextEntry untuk Product Info
- [ ] Tambahkan TextEntry dengan formatting untuk Pricing & Stock
- [ ] Tambahkan ImageEntry untuk gambar
- [ ] Tambahkan IconEntry untuk boolean status
- [ ] Format harga menjadi "Rp X.XXX.XXX"
- [ ] Format stock dengan "X unit"
- [ ] Format tanggal dengan "d M Y"
- [ ] Test view product pada 3+ produk
- [ ] Verifikasi styling (badge, color, weight, icon)
- [ ] Ambil screenshot setiap section
- [ ] Update laporan dengan findings & analysis
- [ ] Update README.md dengan Week 8 documentation

---

## 📊 X. Perbandingan Before & After Week 8

### BEFORE (Week 7)
```
View Product (with Form)
├─ Nama: [TextInput - editable]
├─ SKU: [TextInput - editable]
├─ Harga: [TextInput - editable]
├─ Stok: [TextInput - editable]
├─ Gambar: [FileUpload - editable]
├─ Is Active: [Checkbox - editable]
└─ Is Featured: [Checkbox - editable]

Problem: Confusing - user pikir bisa edit
```

### AFTER (Week 8)
```
View Product (with Info List)
├─ Product Info Section
│  ├─ Nama: [Bold, Primary] ← Read-only
│  ├─ SKU: [Badge, Success] ← Read-only
│  ├─ Deskripsi: [Regular] ← Read-only
│  └─ Tanggal: [d M Y] ← Read-only
├─ Pricing & Stock Section
│  ├─ Harga: Rp X.XXX.XXX [Icon $] ← Read-only
│  └─ Stok: X unit [Icon Cube] ← Read-only
└─ Image and Status Section
   ├─ Gambar: [Displayed] ← Read-only
   ├─ Is Active: ✓/✗ ← Read-only
   └─ Is Featured: ✓/✗ ← Read-only

Benefit: Professional, clear, well-structured
```

---

## 🎓 Y. Learning Summary Week 8

### Konsep yang Dipelajari ✅
- Perbedaan Form (edit) vs Info List (display)
- Penggunaan TextEntry, ImageEntry, IconEntry
- Data formatting dengan formatStateUsing()
- Styling dengan badge, color, weight, icon
- Boolean display dengan IconEntry
- Tanggal formatting
- Professional UI/UX design

### Skills yang Dikuasai ✅
- Read Info List documentation
- Implement complex Info List
- Format berbagai tipe data
- Design professional detail page
- Testing Info List functionality

---

# 🎓 Pertemuan 9 - Tabs in Details Deep Dive

## 🔍 Analisis & Diskusi (Bagian K)

### 1. Kapan kita menggunakan Tabs dibanding Section?

**Tabs** sebaiknya digunakan ketika:
- Data yang ditampilkan **banyak dan bisa dikelompokkan** ke dalam beberapa kategori yang jelas
- Pengguna **tidak perlu melihat semua informasi sekaligus** dan cukup fokus pada satu kategori pada satu waktu
- Ingin **mengurangi scrolling** pada halaman yang panjang
- Ingin tampilan yang **lebih modern dan interaktif**

**Section** sebaiknya digunakan ketika:
- Data yang ditampilkan **relatif sedikit** sehingga semua bisa tampil tanpa scroll panjang
- Pengguna **perlu melihat keseluruhan informasi** secara bersamaan (misalnya untuk perbandingan)
- Informasi bersifat **sekuensial** dan perlu dibaca dari atas ke bawah
- Konteks data saling berkaitan erat sehingga pemisahan ke tab justru mengganggu

**Kesimpulan:** Tabs cocok untuk data yang banyak dan terkategoris, Section cocok untuk data sedikit yang perlu dilihat sekaligus.

---

### 2. Apa kelebihan Tabs untuk data panjang?

Kelebihan Tabs untuk data panjang:

1. **Mengurangi scrolling** - Pengguna tidak perlu scroll panjang untuk menemukan informasi yang dicari, cukup klik tab yang sesuai
2. **Navigasi cepat** - Pengguna bisa langsung melompat ke kategori informasi yang diinginkan tanpa harus melewati informasi lain
3. **Tampilan lebih ringkas** - Hanya menampilkan informasi yang relevan pada satu waktu, mengurangi cognitive load
4. **Pengelompokan logis** - Memaksa developer untuk mengorganisir data ke dalam kategori yang logis dan terstruktur
5. **UX lebih profesional** - Antarmuka yang rapi dengan tab terlihat lebih profesional dibanding halaman panjang yang harus di-scroll
6. **Menghemat ruang** - Informasi yang ditampilkan per tab lebih sedikit, sehingga layout lebih bersih

---

### 3. Apakah Tabs bisa digunakan pada Form juga?

**Ya, Tabs bisa digunakan pada Form.** Filament mendukung penggunaan Tabs baik pada **Info List** (view/display) maupun pada **Form** (input/edit).

Pada Form, Tabs berfungsi untuk:
- **Membagi form yang kompleks** menjadi beberapa bagian yang lebih mudah dikelola
- **Mengurangi jumlah field yang terlihat sekaligus**, sehingga pengguna tidak kewalahan
- **Mengelompokkan field yang berkaitan** dalam satu tab (misal: data pribadi, alamat, pekerjaan)

Contoh penggunaan Tabs pada Form:
```php
use Filament\Schemas\Components\Tabs;

Tabs::make('Form Tabs')
    ->tabs([
        Tabs\Tab::make('Personal Info')
            ->icon('heroicon-o-user')
            ->schema([
                TextInput::make('name'),
                TextInput::make('email'),
            ]),
        Tabs\Tab::make('Address')
            ->icon('heroicon-o-map-pin')
            ->schema([
                TextInput::make('street'),
                TextInput::make('city'),
            ]),
    ])
```

Perbedaan dengan Wizard: Tabs memungkinkan pengguna berpindah tab secara bebas, sedangkan Wizard mengharuskan pengguna mengisi step secara berurutan.

---

### 4. Bagaimana jika tab terlalu banyak?

Jika tab terlalu banyak, beberapa masalah yang muncul:

1. **Tab bar menjadi penuh** - Tab-tab akan terlihat berjejal dan label bisa terpotong, sulit dibaca
2. **Navigasi membingungkan** - Pengguna kesulitan menemukan informasi yang dicari di antara banyak tab
3. **Cognitive overload** - Terlalu banyak pilihan membuat pengguna bingung harus mulai dari mana

**Solusi jika tab terlalu banyak:**

- **Gunakan orientasi vertical** - Tab vertical lebih cocok untuk jumlah tab yang banyak karena label bisa ditampilkan lebih panjang tanpa terpotong
- **Gabungkan kategori** - Kurangi jumlah tab dengan menggabungkan kategori yang berkaitan erat
- **Gunakan sub-tab (nested tabs)** - Kelompokkan tab ke dalam tab utama, sehingga ada tab di dalam tab
- **Gunakan icon + label pendek** - Icon membantu identifikasi tab lebih cepat tanpa harus membaca label panjang
- **Pertimbangkan kembali apakah Tabs tepat** - Jika informasi memang sangat banyak, mungkin lebih baik menggunakan halaman terpisah (navigasi ke page lain) daripada menumpuk semuanya dalam satu halaman dengan banyak tab
- **Gunakan dropdown tab** - Beberapa UI framework menyediakan tab dengan dropdown untuk tab yang overflow

---

## 📝 N. Referensi & Resources

- [Filament Documentation - Wizard](https://filamentphp.com/docs/forms/layout/wizard)
- [Laravel Validation](https://laravel.com/docs/validation)
- [Filament Tables](https://filmentphp.com/docs/tables/overview)
- [File Storage di Laravel](https://laravel.com/docs/filesystem)

---

**Laporan dibuat oleh:** Rifo Anggi Barbara Danuarta  
**Kelas:** 2F   
**Dosen Pengampu:** Habibie Ed Dien
**Program Studi:** Teknik Informatika  

---

*Praktikum selesai! Semoga bermanfaat* 🎉
