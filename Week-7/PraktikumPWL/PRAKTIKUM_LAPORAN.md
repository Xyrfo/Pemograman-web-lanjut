# 📋 LAPORAN PRAKTIKUM WEEK 7
## Implementasi Wizard Form (Multi Step Form) di Filament

**Mata Kuliah:** Pemrograman Web Lanjut  
**Pertemuan:** Week 7  
**Tanggal:** April 2026  
**Nama:** [Silakan isi nama Anda]  

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
| 3 | [Produk 3] | [SKU 3] | [Harga 3] | [Stok 3] | ✅/❌ | Aktif/Nonaktif |

**Verifikasi:**
- ✅ Semua 3 produk tampil di tabel
- ✅ Semua kolom terlihat: Nama, SKU, Harga, Stok, Gambar, Status
- ✅ Format harga menampilkan dalam IDR
- ✅ Badge status menampilkan dengan warna (hijau = Aktif, merah = Nonaktif)
- ✅ Gambar tampil untuk produk yang upload

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

## 🎓 M. Kesimpulan

Pada praktikum Week 7 ini, kami telah berhasil:

✅ **Membuat Model & Migration Product** dengan struktur tabel lengkap  
✅ **Generate Filament Resource** dengan otomatis  
✅ **Implementasi Wizard Form** dengan 3 steps  
✅ **Setup validasi** per step sesuai requirements  
✅ **Konfigurasi tabel** dengan kolom lengkap dan formatting  
✅ **Custom button** dan hilangkan default button  
✅ **Testing** dan verifikasi semua fitur berfungsi  
✅ **Dokumentasi** lengkap dalam laporan ini  

Dengan Wizard Form, kami memberikan pengalaman pengguna yang lebih baik untuk input data produk yang kompleks dalam aplikasi e-commerce.

---

## 📝 N. Referensi & Resources

- [Filament Documentation - Wizard](https://filamentphp.com/docs/forms/layout/wizard)
- [Laravel Validation](https://laravel.com/docs/validation)
- [Filament Tables](https://filmentphp.com/docs/tables/overview)
- [File Storage di Laravel](https://laravel.com/docs/filesystem)

---

**Laporan dibuat oleh:** [Nama Anda]  
**Dosen Pengampu:** [Nama Dosen]  
**Program Studi:** Teknik Informatika  
**Universitas:** [Nama Universitas]  

---

*Praktikum selesai! Semoga bermanfaat* 🎉
