# 🎓 Pertemuan 15 - Implementasi Many-to-Many Relationship pada Filament

## Identitas Mahasiswa
| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

---

## 📚 Capaian Pembelajaran

Setelah mengikuti praktikum ini, mahasiswa mampu:

1. ✅ Memahami konsep Many-to-Many Relationship pada database
2. ✅ Membuat tabel relasi (pivot table) pada Laravel
3. ✅ Menghubungkan model menggunakan `belongsToMany`
4. ✅ Menggunakan multiple select relationship pada form Filament
5. ✅ Membuat Tag Resource pada Filament Admin Panel
6. ✅ Mengelola relasi menggunakan Relationship Manager

---


## 🎯 Latar Belakang (Bagian A)

### Konsep Many-to-Many Relationship

Dalam sistem blog, **satu Post dapat memiliki banyak Tag**, dan **satu Tag dapat digunakan oleh banyak Post**. Relasi tersebut disebut **Many-to-Many Relationship**.

**Contoh Data:**

| Post | Tags |
|------|------|
| Laravel CRUD | Laravel, PHP |
| Laravel Tutorial | Laravel |
| PHP Basic | PHP |

**Diagram Relasi:**

```
Post
  ↕
Many To Many
  ↕
Tag
```

Relasi ini membutuhkan **pivot table** untuk menghubungkan kedua tabel.

---

## 🔧 Permasalahan Penyimpanan Tag (Bagian B)

### Metode Lama: JSON Column

Sebelumnya tag disimpan dalam format JSON di tabel `posts`:

```
tags
---------
["Laravel 12","PHP"]
```

**Kelemahan Metode JSON:**

| Kelemahan | Deskripsi |
|-----------|----------|
| ❌ Tidak terstruktur | Data sulit dimodifikasi dan divalidasi |
| ❌ Data duplikat | Tag yang sama mungkin disimpan berkali-kali |
| ❌ Query sulit | Pencarian dan filtering data kompleks |
| ❌ Tidak efisien | Tidak optimal untuk database normalisasi |

### Solusi: Many-to-Many Relationship dengan Pivot Table

Menggunakan structured database dengan tabel pivot untuk menghubungkan posts dan tags secara proper.

---

## 💾 Struktur Database Many-to-Many (Bagian C)

### 1️⃣ Tabel Posts

```sql
posts
---------
id (bigint unsigned)
title (varchar(255))
slug (varchar(255))
category_id (int)
color (varchar(255)) - nullable
image (varchar(255)) - nullable
body (text) - nullable
published (tinyint)
published_at (date) - nullable
created_at (timestamp)
updated_at (timestamp)
```

### 2️⃣ Tabel Tags

```sql
tags
---------
id (bigint unsigned)
name (varchar(255))
created_at (timestamp)
updated_at (timestamp)
```

### 3️⃣ Pivot Table (post_tag)

```sql
post_tag
---------
post_id (bigint unsigned) - Foreign Key → posts.id
tag_id (bigint unsigned) - Foreign Key → tags.id
Primary Key: (post_id, tag_id)
```

**Diagram ER:**

```
┌─────────────────────┐
│ posts               │
│ - id (PK)           │
│ - title             │
│ - slug              │
│ - category_id (FK)  │
│ - color             │
│ - image             │
│ - body              │
│ - published         │
│ - published_at      │
└──────────┬──────────┘
           │ (1:M)
           │
    ┌──────▼──────┐
    │  post_tag   │ (Pivot Table)
    │ - post_id   │
    │ - tag_id    │
    └──────┬──────┘
           │ (M:1)
           │
┌──────────▼──────────┐
│ tags                │
│ - id (PK)           │
│ - name              │
└─────────────────────┘
```

---

## 🔄 Langkah-Langkah Praktikum

### Step D: Rollback Migration (Opsional)
Jika sebelumnya menggunakan JSON column pada tags, jalankan rollback:

```bash
php artisan migrate:rollback
```

**Catatan:** Hapus kolom `$table->json('tags');` pada `create_posts_table.php`

---

### Step E: Membuat Tabel Tags ✅

**File:** `database/migrations/2026_05_30_000001_create_tags_and_post_tag_tables.php`

```php
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
});
```

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

### Step F: Membuat Pivot Table ✅

**File:** `database/migrations/2026_05_30_000001_create_tags_and_post_tag_tables.php`

```php
Schema::create('post_tag', function (Blueprint $table) {
    $table->foreignId('post_id')
          ->constrained()
          ->cascadeOnDelete();
    $table->foreignId('tag_id')
          ->constrained()
          ->cascadeOnDelete();
    $table->primary(['post_id', 'tag_id']);
});
```

**Penjelasan:**
- `foreignId('post_id')` — Membuat kolom foreign key ke tabel posts
- `constrained()` — Otomatis mencari nama tabel (posts)
- `cascadeOnDelete()` — Jika post dihapus, data di pivot table juga terhapus
- `primary(['post_id', 'tag_id'])` — Composite primary key

---

### Step G: Jalankan Migration ✅

```bash
php artisan migrate
```

**Output yang diharapkan:**
```
INFO  Nothing to migrate.
```

*(Sudah dijalankan sebelumnya)*

---

### Step H: Membuat Model Tag ✅

**File:** `app/Models/Tag.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
```

---

### Step I: Menambahkan Relationship pada Model Post ✅

**File:** `app/Models/Post.php`

```php
public function tags(): BelongsToMany
{
    return $this->belongsToMany(Tag::class, 'post_tag');
}
```

**Catatan:** Hapus `'tags'` dari `$fillable` karena tags sekarang bukan JSON column.

---

### Step J: Menambahkan Relationship pada Model Tag ✅

**File:** `app/Models/Tag.php`

```php
public function posts(): BelongsToMany
{
    return $this->belongsToMany(Post::class, 'post_tag');
}
```

---

### Step K: Membuat Tag Resource pada Filament ✅

```bash
php artisan make:filament-resource Tag
```

**Output:**
```
INFO Filament resource [App\Filament\Resources\Tags\TagResource] created successfully.
```

---

### Step L: Mengkonfigurasi Form Tag ✅

**File:** `app/Filament/Resources/Tags/Schemas/TagForm.php`

```php
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TagForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
```

---

### Step M: Mengkonfigurasi Table Tag ✅

**File:** `app/Filament/Resources/Tags/Tables/TagsTable.php`

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
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
```

---

### Step N: Menggunakan Relationship pada Form Post ✅

**File:** `app/Filament/Resources/Posts/Schemas/PostForm.php`

Ganti field `TagsInput::make('tags')` dengan:

```php
Select::make('tags')
    ->relationship('tags', 'name')
    ->multiple()
    ->preload()
    ->searchable()
    ->label('Tags'),
```

**Penjelasan:**
- `relationship('tags', 'name')` — Mengambil relasi `tags()` dari Post model, tampilkan field `name`
- `multiple()` — Memungkinkan memilih lebih dari satu tag
- `preload()` — Preload data tags saat halaman load
- `searchable()` — Memungkinkan pencarian tag

---

### Step O: Membuat Relationship Manager ✅

```bash
php artisan make:filament-relation-manager PostResource tags name
```

**File:** `app/Filament/Resources/Posts/RelationManagers/TagsRelationManager.php`

```php
<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use App\Filament\Resources\Tags\TagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    protected static ?string $relatedResource = TagResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
```

---

### Step P: Mendaftarkan Relationship Manager di PostResource ✅

**File:** `app/Filament/Resources/Posts/PostResource.php`

```php
use App\Filament\Resources\Posts\RelationManagers\TagsRelationManager;

public static function getRelations(): array
{
    return [
        TagsRelationManager::class,
    ];
}
```

---

## � Data Sample yang Dibuat ✅

Menggunakan Laravel Tinker, saya telah membuat 5 sample tags:

| ID | Name | Created | Updated |
|----|------|---------|---------|
| 1 | Laravel 10 | ✅ | ✅ |
| 2 | Laravel 11 | ✅ | ✅ |
| 3 | Laravel 12 | ✅ | ✅ |
| 4 | PHP | ✅ | ✅ |
| 5 | Backend | ✅ | ✅ |

**Perintah Tinker:**

```bash
php artisan tinker
```

```php
App\Models\Tag::create(['name' => 'Laravel 10'])
App\Models\Tag::create(['name' => 'Laravel 11'])
App\Models\Tag::create(['name' => 'Laravel 12'])
App\Models\Tag::create(['name' => 'PHP'])
App\Models\Tag::create(['name' => 'Backend'])
exit
```

---

## ✅ Latihan Praktikum

| No | Aktivitas | Status |
|----|-----------|--------|
| 1 | Buat tabel tags dan post_tag | ✅ Selesai |
| 2 | Buat model Tag dengan relasi belongsToMany | ✅ Selesai |
| 3 | Update Post model dengan relasi belongsToMany | ✅ Selesai |
| 4 | Buat Tag Resource pada Filament | ✅ Selesai |
| 5 | Update Post form dengan multiple select tags | ✅ Selesai |
| 6 | Buat TagsRelationManager untuk Post resource | ✅ Selesai |
| 7 | Buat 5 sample tags di database | ✅ Selesai |

---

## 🗂️ Kode Akhir

### 1️⃣ Model Post (`app/Models/Post.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'color',
        'image',
        'body',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published'    => 'boolean',
        'published_at' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
```

---

### 2️⃣ Model Tag (`app/Models/Tag.php`)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_tag');
    }
}
```

---

### 3️⃣ Migration Tags dan Pivot Table

**File:** `database/migrations/2026_05_30_000001_create_tags_and_post_tag_tables.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('tag_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('tags');
    }
};
```

---

### 4️⃣ Tag Resource (`app/Filament/Resources/Tags/TagResource.php`)

```php
<?php

namespace App\Filament\Resources\Tags;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Resources\Tags\Schemas\TagForm;
use App\Filament\Resources\Tags\Tables\TagsTable;
use App\Models\Tag;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return TagForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TagsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit'   => EditTag::route('/{record}/edit'),
        ];
    }
}
```

---

### 5️⃣ Post Form dengan Multiple Select Tags

**File:** `app/Filament/Resources/Posts/Schemas/PostForm.php`

```php
// Bagian yang berkaitan dengan tags
Select::make('tags')
    ->relationship('tags', 'name')
    ->multiple()
    ->preload()
    ->searchable()
    ->label('Tags'),
```

---

### 6️⃣ Tags Relationship Manager

**File:** `app/Filament/Resources/Posts/RelationManagers/TagsRelationManager.php`

```php
<?php

namespace App\Filament\Resources\Posts\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TagsRelationManager extends RelationManager
{
    protected static string $relationship = 'tags';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                // columns
            ])
            ->filters([
                // filters
            ])
            ->headerActions([
                // actions
            ])
            ->actions([
                // table actions
            ])
            ->bulkActions([
                // bulk actions
            ]);
    }
}
```

---

## 🔍 Analisis & Diskusi

### Pertanyaan 1: Perbedaan HasMany vs Many-to-Many

| Aspek | HasMany (1:M) | Many-to-Many (M:M) |
|-------|---------------|------------------|
| **Relasi** | 1 Category → banyak Posts | 1 Post ↔ banyak Tags & 1 Tag ↔ banyak Posts |
| **Tabel** | Foreign key di tabel child | Pivot table terpisah |
| **Foreign Key** | `category_id` di tabel posts | `post_id` & `tag_id` di `post_tag` |
| **Kepemilikan** | Post dimiliki oleh Category | Post dan Tag independen, terhubung via pivot |
| **Operasi** | `$post->category()` | `$post->tags()` |
| **Cascade** | Post dihapus jika category dihapus (optional) | Post & tag tetap, relasi di pivot dihapus |

**Kesimpulan:** HasMany untuk relasi ketergantungan (parent-child), Many-to-Many untuk relasi kesetaraan (peer-to-peer).

---

### Pertanyaan 2: Mengapa Pivot Table Diperlukan?

1. **Efisiensi Database**
   - Menyimpan relasi terstruktur tanpa redundansi
   - JSON column menyebabkan duplikasi data

2. **Integrity & Validasi**
   - Foreign key constraint menjamin data consistency
   - Tidak bisa menyimpan tag_id yang tidak ada

3. **Query Flexibility**
   - Mudah mencari posts dengan tag tertentu
   - Mudah mencari tags yang digunakan berapa kali

4. **Cascade Operations**
   - Menghapus tag akan otomatis menghapus relasinya dengan posts
   - Menghapus post akan otomatis menghapus relasinya dengan tags

5. **Normalisasi Database**
   - Mematuhi normal form yang baik (3NF)
   - Menghindari update anomalies

**Contoh Query:**

```php
// Cari posts dengan tag tertentu
$posts = Post::whereHas('tags', function ($query) {
    $query->where('name', 'Laravel');
})->get();

// Cari tags yang paling sering digunakan
$popularTags = Tag::withCount('posts')
    ->orderByDesc('posts_count')
    ->get();
```

---

### Pertanyaan 3: Fungsi attach() dan detach() pada Filament

**`attach()` — Menambahkan relasi**

```php
$post = Post::find(1);
$post->tags()->attach([2, 3]); // Attach tag ID 2 dan 3
```

Filament otomatis menggunakan `attach()` ketika:
- Memilih tag dari dropdown di form Post
- Klik tombol "Attach" di dalam RelationManager

**`detach()` — Menghapus relasi**

```php
$post = Post::find(1);
$post->tags()->detach(2); // Detach tag ID 2
$post->tags()->detach(); // Detach semua tags
```

Filament otomatis menggunakan `detach()` ketika:
- Uncheck tag dari dropdown
- Klik tombol Delete di RelationManager

**Perbedaan dengan delete():**
- `detach()` — Hanya menghapus relasi di pivot table, POST dan TAG tetap ada
- `delete()` — Menghapus model itu sendiri dari database

---

### Pertanyaan 4: Mengapa JSON Column Kurang Baik untuk Relasi?

| Masalah | JSON Column | Pivot Table |
|--------|-----------|-----------|
| **Duplikasi** | "Laravel 12" disimpan di setiap post | Disimpan sekali di tabel tags |
| **Update** | Harus update di setiap post | Update sekali di tabel tags |
| **Query** | LIKE search lambat | Indexed queries cepat |
| **Validasi** | Tidak ada constraint | Foreign key guarantee |
| **Relasi Balik** | Tidak bisa cari "posts dengan tag X" | Easy dengan whereHas() |
| **Ukuran DB** | Besar karena duplikasi | Kecil & efficient |

**Skenario Nyata:**

Jika "Laravel 11" digunakan oleh 1000 posts dengan JSON:
- String "Laravel 11" disimpan 1000 kali
- Storage: ~11KB × 1000 = 11MB

Dengan pivot table:
- "Laravel 11" disimpan 1 kali di tabel tags
- 1000 baris di post_tag (hanya integer IDs)
- Storage: ~100 bytes + 4KB = ~4.1KB
- **Hemat ~99% storage!**

---

## 💡 Fitur-Fitur Relationship Manager Tags

### Fitur 1: Inline Management
- ✅ Mengelola tags langsung dari halaman edit post
- ✅ Tidak perlu berpindah halaman
- ✅ Perubahan langsung tersimpan

### Fitur 2: Multiple Select
- ✅ Memilih lebih dari satu tag
- ✅ Searchable untuk kemudahan pencarian
- ✅ Preload data untuk performa optimal

### Fitur 3: Quick Actions
- ✅ Tombol "New tag" untuk membuat tag dari form Post
- ✅ Tombol "Edit" untuk mengubah tag
- ✅ Tombol "Delete" untuk menghapus relasi tag

### Fitur 4: Batch Operations
- ✅ Bulk delete relasi
- ✅ Bulk attach/detach tags

---

## 🎯 Cara Menggunakan Fitur

### 1. Membuat Tag Baru

**Via Tag Resource:**
1. Buka **Admin Panel** → **Tags**
2. Klik **+ New tag**
3. Isi field **Name** (contoh: "Vue.js")
4. Klik **Save**

**Via Form Post:**
1. Edit Post
2. Scroll ke field **Tags**
3. Ketik nama tag baru
4. Tekan Enter (jika ada fitur "Create" enabled)

---

### 2. Menambahkan Tag ke Post

**Metode 1: Via Post Form**
1. Buka **Posts** → Edit post
2. Pada field **Tags**, pilih tag dari dropdown
3. Bisa pilih multiple tags
4. Klik **Save**

**Metode 2: Via RelationManager**
1. Edit post
2. Scroll ke section **Tags** (RelationManager panel)
3. Klik **+ Attach tag**
4. Pilih tags dari modal
5. Klik **Attach**

---

### 3. Menghapus Tag dari Post

**Via Form:**
- Uncheck tag pada dropdown, lalu Save

**Via RelationManager:**
- Klik tombol **Delete** pada tag yang ingin dihapus

**Catatan:** Tag masih ada di database, hanya relasi dengan post yang dihapus.

---

### 4. Menghapus Tag Sepenuhnya

1. Buka **Admin Panel** → **Tags**
2. Cari tag yang ingin dihapus
3. Klik **Delete**
4. Tag akan dihapus dari database bersama relasi-relasinya (cascadeOnDelete)

---

## 🎓 Kesimpulan

### Pencapaian Praktikum

✅ **Berhasil mengimplementasikan Many-to-Many Relationship** antara Posts dan Tags dengan:
- Database schema yang terstruktur (pivot table)
- Model yang proper (belongsToMany)
- Filament Resource untuk Tag management
- Form dengan multiple select tags
- Inline Relationship Manager untuk quick management
- 5 sample tags yang siap digunakan

### Key Learnings

1. **Pivot Table adalah fondasi** Many-to-Many relationship
2. **Foreign key dengan cascade** menjaga data integrity
3. **Multiple select dengan relationship()** sangat mudah di Filament
4. **RelationManager** meningkatkan UX untuk mengelola relasi
5. **Many-to-Many lebih scalable** dibanding JSON column

### Aplikasi Praktis

Konsep ini bisa diterapkan di banyak skenario:
- **Books & Authors** (1 book banyak authors, 1 author banyak books)
- **Users & Roles** (1 user banyak roles, 1 role banyak users)
- **Products & Categories** (1 product banyak categories, 1 category banyak products)
- **Posts & Tags** ← Yang kita implementasikan ✅

### Next Steps

Pengembangan lebih lanjut:
- Tambah metadata di pivot table (contoh: `order`, `featured`)
- Tambah filtering & searching by tags
- Implementasi polymorphic many-to-many
- Cache frequently used tags

---

**Dibuat dengan ❤️ untuk pembelajaran Laravel & Filament**

*Pertemuan 15 - Implementasi Many-to-Many Relationship pada Filament*

*Semester 4 - Pemrograman Web Lanjut*
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


