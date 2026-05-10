# 📚 Pemrograman Web Lanjut — Semester 4

## Identitas Mahasiswa

| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

🔗 **Repository GitHub:** [github.com/Xyrfo/Pemograman-web-lanjut](https://github.com/Xyrfo/Pemograman-web-lanjut)

---

## 📖 Daftar Isi

### 📁 [Week-1](Week-1/) — Instalasi Laravel & Pengenalan MVC
- 📄 [README.md](Week-1/README.md) — Identitas Mahasiswa
- 📂 **DokumentasiPWL-Week01/**
  - 📎 LAPORAN HASIL PRAKTIKUM 1.pdf
- 📂 **Materi/**
  - 📎 1.JOBSHEET PRAKTIKUM - Install Filament.pdf
  - 📎 2.JOBSHEET PRAKTIKUM - Membuat CRUD Resource dengan Filament v4.pdf
  - 📎 3.JOBSHEET PRAKTIKUM - Migrasi Dan Model.pdf
  - 📎 PWL - Jobsheet 03 - Migration.pdf
  - 📎 PWL - Jobsheet 04 - Eloquent ORM.pdf

---

### 📁 [Week-2](Week-2/) — Route, Controller & View (MVC)
> Folder Project: `Week-2/POS/`
- 📄 [README.md](Week-2/POS/README.md)
- **Praktikum 1** — Route dasar, Controller, View
- **Praktikum 2** — Relasi Controller & View
- **Praktikum 3** — Pendalaman Route & URL

---

### 📁 [Week-3](Week-3/) — Migration & Database
> Folder Project: `Week-3/PWL_POS/`
- 📄 [README.md](Week-3/PWL_POS/README.md)
- **Praktikum 1** — Migration dasar
- **Praktikum 2** — Konfigurasi database
- **Praktikum 3** — Seeder & Data awal

---

### 📁 [Week-4](Week-4/) — Eloquent ORM & CRUD
> Folder Project: `Week-4/PWL_POS/`
- 📄 [README.md](Week-4/PWL_POS/README.md)
- **Praktikum 1** — Eloquent Model & Tabel User
- **Praktikum 2** — Eloquent Query (find, first, firstWhere, count, dll.)
  - 2.1 — Find & First
  - 2.2 — findOrFail & firstOrFail
  - 2.3 — Count & View Data User
  - 2.4 — firstOrCreate, firstOrNew, save
  - 2.5 — Update & Delete

---

### 📁 [Week-5](Week-5/) — Filament Admin Panel & Resource CRUD
> Folder Project: `Week-5/PraktikumPWL/`
- 📄 [README.md](Week-5/PraktikumPWL/README.md)
- **Praktikum 1** — Install Filament, Halaman Login, Panel Builder
  - Analisis: Kelebihan Filament, Livewire, SQLite vs MySQL, Panel Builder
- **Praktikum 2** — Membuat CRUD Resource (User)
  - Form Schema & Table Schema, Validasi Email Unique, Password Hashing
- **Praktikum 3** — Model & Migrasi (fillable, casts, foreign key)
  - Analisis: $fillable, $casts, integer vs foreign key, onDelete behavior

---

### 📁 [Week-6](Week-6/) — Form Elements, Layout & Validasi
> Folder Project: `Week-6/PraktikumPWL/`
- 📄 [README.md](Week-6/PraktikumPWL/README.md)
- **Praktikum 6A** — Implementasi Form Elements & Resource Post
  - TextInput, Select (Relasi), ColorPicker, MarkdownEditor/RichEditor
  - FileUpload, TagsInput, Checkbox, DatePicker
  - Menampilkan data di tabel (TextColumn, ColorColumn, ImageColumn)
- **Praktikum 6B** — Custom Layout Form (Section & Group)
  - Columns, Section, ColumnSpan, Group
  - Analisis: Layout form, Section vs Group, columnSpanFull, Grid 12 kolom
- **Praktikum 6C** — Implementasi Form Validation
  - Required, MinLength, MaxLength, Unique (ignoreRecord)
  - FileUpload validation, Custom Error Messages
  - Analisis: Client-side vs Server-side, rules array vs shortcut method

---

### 📁 [Week-7](Week-7/) — Wizard Form, Info List & Tabs
> Folder Project: `Week-7/PraktikumPWL/`
- 📄 [README.md](Week-7/PraktikumPWL/README.md)
- 📄 [PRAKTIKUM_LAPORAN.md](Week-7/PraktikumPWL/PRAKTIKUM_LAPORAN.md)

#### Pertemuan 7 — Wizard Form (Multi Step Form)
- **Step 1: Product Info** — Nama, SKU, Deskripsi
- **Step 2: Pricing & Stock** — Harga (IDR), Stok
- **Step 3: Media & Status** — Upload gambar, is_active, is_featured
- Model Product, Migration, Resource ProductResource

#### Pertemuan 8 — Info List (View Page)
- TextEntry, ImageEntry, IconEntry
- Badge, Color, Weight, Icon, Date formatting
- Section: Product Info, Pricing & Stock, Image and Status
- Format harga Rupiah, boolean icon (✓/✗)

#### Pertemuan 9 — Tabs in Details Deep Dive
- Mengubah Section menjadi Tabs
- Tab: Product Info, Pricing & Stock, Media & Status
- Icon & Badge pada Tab, Orientasi Horizontal & Vertical
- Badge dinamis berdasarkan stok, BadgeColor dinamis (success/warning/danger)

---

### 📁 [Week-10](Week-10/) — Sorting (Ascending & Descending)
> Folder Project: `Week-10/PraktikumPWL/`
- 📄 [README.md](Week-10/PraktikumPWL/README.md)
- **Step C** — Sorting pada kolom Title (`sortable()`)
- **Step D** — Sorting pada kolom Slug
- **Step E** — Sorting pada kolom relasi Category (`category.name`)
- **Step F** — Sorting pada kolom tanggal (`dateTime()`)
- **Step G-H** — Default sorting (`defaultSort('created_at', 'desc')`)
- Analisis: Pentingnya sorting, sortable vs defaultSort, sorting relasi, kapan desc

---

### 📁 [Week-11](Week-11/) — Search & Filter
> Folder Project: `Week-11/PraktikumPWL/`
- 📄 [README.md](Week-11/PraktikumPWL/README.md)
- **Step B** — Search pada kolom (`searchable()`) — Title, Slug, Category
- **Step C** — Filter berdasarkan tanggal (DatePicker + `whereDate()`)
- **Step D** — Filter berdasarkan relasi kategori (`SelectFilter`)
- **Step E** — Perbandingan Search vs Filter
- Analisis: Search vs Filter tanggal, relationship(), whereDate(), searchable vs filters

---

### 📁 [Week-12](Week-12/) — Toggle Column & Column Visibility
> Folder Project: `Week-12/PraktikumPWL/`
- 📄 [README.md](Week-12/PraktikumPWL/README.md)
- **Step B** — ToggleColumn pada PostsTable (`published`)
- **Step C** — `toggleable()` pada semua kolom PostsTable + `isToggledHiddenByDefault`
- **Step D** — ToggleColumn pada ProductsTable (`is_active`, `is_featured`)
- **Step E** — `toggleable()` pada semua kolom ProductsTable
- Analisis: ToggleColumn vs toggleable(), toggledHiddenByDefault, auto-save via AJAX

---

### 📁 [Week-13](Week-13/) — Table Actions in Depth
> Folder Project: `Week-13/PraktikumPWL/`
- 📄 [README.md](Week-13/PraktikumPWL/README.md)
- **Step B** — Record Actions dengan ActionGroup (View, Edit, Delete)
- **Step C** — Custom Action: Clone Post (replicate, modal konfirmasi, Notification)
- **Step D** — Delete Action dengan modal description dinamis
- **Step E** — Bulk Actions: Publish & Unpublish Selected (Collection, deselectRecordsAfterCompletion)
- **Step F** — Table Actions pada ProductsTable (toggleActive, toggleFeatured, Bulk Activate/Deactivate/Feature)
- Analisis: ActionGroup, requiresConfirmation, Record vs Bulk Action, Notification


---

## 🛠️ Tech Stack

| Teknologi | Versi |
|-----------|-------|
| **PHP** | 8.1+ |
| **Laravel** | 11 |
| **Filament** | v4 |
| **Livewire** | 3 |
| **MySQL/MariaDB** | 5.7+ |
| **Node.js** | 16+ |

## 🚀 Cara Menjalankan

```bash
# Masuk ke folder project minggu tertentu
cd Week-X/PraktikumPWL

# Install dependencies
composer install
npm install

# Generate key & link storage
php artisan key:generate
php artisan storage:link

# Jalankan migration
php artisan migrate

# Jalankan server
php artisan serve
```
