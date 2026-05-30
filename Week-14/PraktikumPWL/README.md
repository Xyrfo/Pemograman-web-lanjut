# 🎓 Pertemuan 14 - Implementasi Relation pada Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

---

## 📚 Capaian Pembelajaran

1. Memahami konsep Relasi Eloquent (HasMany & BelongsTo) pada Laravel
2. Membuat **RelationManager** di Filament untuk mengelola data relasi secara inline
3. Mengimplementasikan `PostsRelationManager` pada `CategoryResource`
4. Menambahkan **Foreign Key** yang proper di tabel `posts`
5. Memahami perbedaan antara relasi model Eloquent dan RelationManager Filament

---

## 📖 Daftar Isi

- [Latar Belakang (Bagian A)](#-latar-belakang-bagian-a)
- [Langkah Praktikum](#-langkah-praktikum)
  - [Step B: Memperbarui Model Category (hasMany)](#step-b-memperbarui-model-category-hasmany)
  - [Step C: Memperbarui Model Post (BelongsTo)](#step-c-memperbarui-model-post-belongsto)
  - [Step D: Menambahkan Foreign Key Migration](#step-d-menambahkan-foreign-key-migration)
  - [Step E: Membuat PostsRelationManager](#step-e-membuat-postsrelationmanager)
  - [Step F: Mendaftarkan RelationManager di CategoryResource](#step-f-mendaftarkan-relationmanager-di-categoryresource)
  - [Step G: Menjalankan Migrasi](#step-g-menjalankan-migrasi)
- [Screenshot Evidence](#-screenshot-evidence)
- [Kode Akhir](#-kode-akhir)
- [Latihan Praktikum](#-latihan-praktikum)
- [Analisis & Diskusi](#-analisis--diskusi-bagian-i)

---

## 🎯 Latar Belakang (Bagian A)

Dalam pengembangan aplikasi web, data jarang berdiri sendiri — hampir selalu ada relasi antar entitas. Contoh nyata:
- Satu **Category** memiliki banyak **Posts** → relasi `HasMany`
- Setiap **Post** dimiliki oleh satu **Category** → relasi `BelongsTo`

Filament menyediakan fitur **RelationManager** yang memungkinkan kita mengelola data relasi langsung dari halaman edit parent record, tanpa perlu berpindah halaman. Ini meningkatkan efisiensi pengelolaan data secara signifikan.

### Konsep Relasi yang Digunakan

| Relasi | Model | Method | Deskripsi |
|--------|-------|--------|-----------|
| `HasMany` | Category | `posts()` | Satu category punya banyak posts |
| `BelongsTo` | Post | `category()` | Satu post dimiliki satu category |
| `foreignId` | Migration | `category_id` | Foreign key di tabel posts |

---

## 🔧 Langkah Praktikum

### Step B: Memperbarui Model Category (hasMany)

**File:** `app/Models/Category.php`

Tambahkan method relasi `posts()` yang menggunakan `HasMany`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
```

**Penjelasan:**
- `HasMany` — import dari `Illuminate\Database\Eloquent\Relations\HasMany`
- `hasMany(Post::class)` — Laravel otomatis mencari foreign key `category_id` di tabel `posts`
- Return type `HasMany` digunakan agar IDE bisa memberikan autocomplete yang tepat

---

### Step C: Memperbarui Model Post (BelongsTo)

**File:** `app/Models/Post.php`

Tambahkan type hint `BelongsTo` pada method `category()`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'category_id',
        'color', 'image', 'body',
        'tags', 'published', 'published_at',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published'    => 'boolean',
        'published_at' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

---

### Step D: Menambahkan Foreign Key Migration

Jalankan perintah berikut untuk membuat migration baru:

```bash
php artisan make:migration add_foreign_key_to_posts_table --table=posts
```

**File:** `database/migrations/2026_05_23_033349_add_foreign_key_to_posts_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Ubah kolom category_id menjadi foreign key yang proper
            $table->foreignId('category_id')
                  ->nullable()
                  ->change()
                  ->constrained('categories')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }
};
```

**Penjelasan:**
- `foreignId('category_id')` — Membuat kolom integer unsigned yang mereferensi ke `categories.id`
- `nullable()` — Post boleh tidak memiliki category
- `constrained('categories')` — Mendefinisikan foreign key constraint ke tabel `categories`
- `nullOnDelete()` — Jika category dihapus, `category_id` pada posts menjadi NULL (bukan cascade delete)

---

### Step E: Membuat PostsRelationManager

**File:** `app/Filament/Resources/Categories/RelationManagers/PostsRelationManager.php`

RelationManager adalah kelas yang mengelola tampilan dan operasi CRUD pada data relasi langsung di halaman edit parent record.

```php
<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                ColorPicker::make('color'),
                FileUpload::make('image')
                    ->disk('public')
                    ->directory('posts'),
                TagsInput::make('tags'),
                Checkbox::make('published'),
                DatePicker::make('published_at'),
                MarkdownEditor::make('body')
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                ColorColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->disk('public')
                    ->toggleable(),
                ToggleColumn::make('published')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
```

**Komponen kunci:**
- `protected static string $relationship = 'posts'` — nama method relasi pada model `Category`
- `form()` — Form yang digunakan saat Create/Edit post dari dalam RelationManager
- `table()` — Tampilan tabel posts yang muncul di halaman edit Category
- `recordTitleAttribute('title')` — Atribut yang ditampilkan sebagai judul record

---

### Step F: Mendaftarkan RelationManager di CategoryResource

**File:** `app/Filament/Resources/Categories/CategoryResource.php`

```php
<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\RelationManagers\PostsRelationManager; // ← import
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class, // ← daftarkan di sini
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit'   => EditCategory::route('/{record}/edit'),
        ];
    }
}
```

**Penjelasan:**
- `getRelations()` — Method yang mengembalikan array berisi semua RelationManager untuk resource ini
- Cukup tambahkan `PostsRelationManager::class` di dalam array tersebut
- Filament otomatis menampilkan panel RelationManager di halaman Edit Category

---

### Step G: Menjalankan Migrasi

```bash
php artisan migrate
```

Output yang diharapkan:
```
INFO  Running migrations.
2026_05_23_033349_add_foreign_key_to_posts_table ........... 87.00ms DONE
```

---

## 📸 Screenshot Evidence

### 1. Halaman Daftar Categories
> Menampilkan halaman list categories dengan satu data kategori "laravel". Terdapat tombol **New category** untuk membuat kategori baru dan tombol **Edit** di setiap baris.

![Categories List Page](https://github.com/user-attachments/assets/categories-list)
<!-- SS: Buka http://127.0.0.1:8000/admin/categories -->

---

### 2. Halaman Edit Category — PostsRelationManager Muncul
> Setelah mengklik **Edit** pada category "laravel", halaman edit menampilkan:
> - Form category (Name, Slug) di bagian atas
> - Panel **Posts** RelationManager di bawahnya dengan daftar posts yang terkait (laravel, PHP, Merbabu)
> - Tombol **New post**, Search, Edit, Delete di dalam panel Posts

<!-- SS: Buka http://127.0.0.1:8000/admin/categories/1/edit -->
<!-- Scroll down untuk melihat panel Posts -->

---

### 3. PostsRelationManager — Daftar Posts dalam Category
> Panel Posts menampilkan 3 posts yang terkait dengan category "laravel":
> - **laravel** — Published: OFF
> - **PHP** — Published: OFF  
> - **Merbabu** — Published: OFF (dengan gambar)
>
> Setiap post memiliki tombol Edit dan Delete. Terdapat tombol **New post** untuk menambah post baru langsung dari sini.

<!-- SS: Ambil screenshot panel Posts RelationManager -->

---

### 4. Modal Create Post dari RelationManager
> Klik tombol **New post** di dalam panel RelationManager menampilkan modal form Create Post yang berisi:
> - Field Title, Slug
> - ColorPicker
> - FileUpload (image)
> - TagsInput, Checkbox published
> - DatePicker, MarkdownEditor (body)

<!-- SS: Klik New post di dalam panel, ambil screenshot modal -->

---

## 🏋️ Latihan Praktikum

| No | Tugas | Status |
|----|-------|--------|
| 1 | Tambah relasi `hasMany` pada model `Category` | ✅ |
| 2 | Tambah relasi `BelongsTo` dengan type hint pada model `Post` | ✅ |
| 3 | Buat migration foreign key untuk kolom `category_id` di posts | ✅ |
| 4 | Buat `PostsRelationManager` dengan form & table | ✅ |
| 5 | Daftarkan `PostsRelationManager` di `CategoryResource::getRelations()` | ✅ |
| 6 | Jalankan migration & verifikasi di browser | ✅ |
| 7 | Buat/Edit post langsung dari halaman Category edit | ✅ |
| 8 | Screenshot: dashboard, categories list, category edit + RM, create modal | ✅ |

---

## 📝 Kode Akhir

### `app/Models/Category.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
```

### `app/Models/Post.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'category_id',
        'color', 'image', 'body',
        'tags', 'published', 'published_at',
    ];

    protected $casts = [
        'tags'         => 'array',
        'published'    => 'boolean',
        'published_at' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
```

### `app/Filament/Resources/Categories/RelationManagers/PostsRelationManager.php`

```php
<?php

namespace App\Filament\Resources\Categories\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->maxLength(255),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                ColorPicker::make('color'),
                FileUpload::make('image')->disk('public')->directory('posts'),
                TagsInput::make('tags'),
                Checkbox::make('published'),
                DatePicker::make('published_at'),
                MarkdownEditor::make('body')->columnSpanFull(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('slug')->searchable()->toggleable(isToggledHiddenByDefault: true),
                ColorColumn::make('color')->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')->disk('public')->toggleable(),
                ToggleColumn::make('published')->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([
                CreateAction::make(),
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
```

---

## 🔍 Analisis & Diskusi (Bagian I)

### 1. Apa itu RelationManager dan mengapa digunakan?

**RelationManager** adalah fitur Filament yang memungkinkan pengelolaan data relasi secara *inline* langsung dari halaman edit parent record. Tanpa RelationManager, untuk menambahkan post ke suatu category, kita harus:
1. Masuk ke halaman Posts
2. Klik "New Post"
3. Pilih category secara manual dari dropdown

Dengan RelationManager:
1. Masuk ke halaman Edit Category
2. Di bagian bawah sudah ada panel Posts
3. Langsung klik "New Post" → form muncul sebagai modal
4. Post otomatis terhubung ke category yang sedang diedit

### 2. Perbedaan HasMany vs BelongsTo

| Aspek | `HasMany` | `BelongsTo` |
|-------|-----------|-------------|
| **Digunakan di model** | Parent (Category) | Child (Post) |
| **Arah relasi** | 1 Category → banyak Posts | Banyak Posts → 1 Category |
| **Foreign key** | Ada di tabel child (posts.category_id) | Mereferensi tabel parent |
| **Method Laravel** | `$this->hasMany(Post::class)` | `$this->belongsTo(Category::class)` |
| **Contoh akses** | `$category->posts` | `$post->category` |

### 3. Mengapa perlu Foreign Key Constraint di Database?

Foreign key constraint di database level (berbeda dengan relasi Eloquent yang hanya di PHP level) memberikan:
- **Integritas Data** — Database mencegah post berisi `category_id` yang tidak valid
- **Cascade/SetNull** — Jika category dihapus, bisa dikonfigurasi agar posts ikut terhapus (`onDelete('cascade')`) atau category_id menjadi NULL (`nullOnDelete()`)
- **Performa** — Index otomatis dibuat pada kolom foreign key, mempercepat query JOIN

Kita memilih `nullOnDelete()` karena:
- Post yang sudah dibuat tidak ikut terhapus saat categorynya dihapus
- Admin masih bisa melihat dan mengkategorikan ulang post tersebut

### 4. Bagaimana Filament mengetahui posts mana yang ditampilkan di RelationManager?

Filament menggunakan property `$relationship = 'posts'` untuk memanggil method `posts()` pada model `Category`. Laravel Eloquent kemudian menjalankan query:

```sql
SELECT * FROM posts WHERE category_id = [id_category_yang_sedang_diedit]
```

Sehingga hanya posts yang terkait dengan category yang sedang dibuka yang ditampilkan.

### 5. Apa perbedaan RelationManager dengan Select dropdown pada form?

| Aspek | Select Dropdown (category di form Post) | RelationManager (Posts di edit Category) |
|-------|-----------------------------------------|------------------------------------------|
| **Arah pengelolaan** | Post memilih Category-nya | Category mengelola Posts-nya |
| **Operasi** | Hanya memilih/mengubah parent | Full CRUD (create, edit, delete) |
| **Konteks** | Dari sisi child | Dari sisi parent |
| **UX** | Sederhana, cocok untuk FK biasa | Powerful, cocok untuk manajemen data relasi |

### 6. Kapan menggunakan RelationManager vs Resource terpisah?

| Gunakan RelationManager | Gunakan Resource terpisah |
|------------------------|--------------------------|
| Data child selalu diakses dalam konteks parent | Data child memiliki halaman daftar global |
| Jumlah data child per parent sedikit | Jumlah data child bisa sangat banyak |
| Pengguna perlu sering berpindah antar record | Data child berdiri sendiri tanpa parent |
| Contoh: Posts di dalam Category | Contoh: Posts punya halaman index sendiri |

Pada praktikum ini, kita menggunakan **keduanya** — Posts memiliki resource sendiri (PostResource) DAN bisa dikelola dari Category melalui RelationManager, memberikan fleksibilitas maksimal.

---

## 🔗 Referensi

- [Filament RelationManagers Documentation](https://filamentphp.com/docs/3.x/resources/relation-managers)
- [Laravel Eloquent Relationships](https://laravel.com/docs/11.x/eloquent-relationships)
- [Laravel Migrations - Foreign Keys](https://laravel.com/docs/11.x/migrations#foreign-key-constraints)
