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

  




## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
