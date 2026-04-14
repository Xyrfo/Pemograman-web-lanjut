
Laporan Praktikum Pemrograman Web Lanjut
Identitas Mahasiswa
Keterangan	Data
Nama	Rifo Anggi Barbara Danuarta
NIM	244107020063
Kelas	TI-2F

Praktikum 6 - Implementasi Form Elements & Resource Post di Filament


A. Membuat Resource Post
Buka terminal dan jalankan perintah untuk membuat resource Post:

Bash
php artisan make:filament-resource Post

Model attribute: title 

Generate read-only page: no 

Generate dari database: no 

File resource baru akan otomatis terbuat di dalam folder app/Filament/Admin/Resources/Posts.
+1


B. Implementasi Form Elements
Buka file PostForm.php yang berada di direktori app/Filament/Admin/Resources/Posts/Schemas/PostForm.php dan tambahkan komponen form berikut:
+2

Text Input (Title & Slug)
Gunakan TextInput untuk judul dan slug, dan atur agar wajib diisi.

PHP
TextInput::make('title')->required(),
TextInput::make('slug')->required(),
Select (Relasi Category)
Gunakan Select untuk mengambil data langsung dari tabel categories.
+1

PHP
Select::make('category_id')
    ->relationship('category', 'name')
    ->preload()
    ->searchable(),

+1

Color Picker
Menambahkan input pemilih warna otomatis dari Filament.
+1

PHP
ColorPicker::make('color'),


Markdown / Rich Editor (Body)
Pilih salah satu editor untuk konten tulisan. Anda bisa menggunakan Markdown Editor atau Rich Editor.
+2

PHP
MarkdownEditor::make('content'),
// Atau RichEditor::make('content'),

+1

File Upload (Image)
Menambahkan form untuk upload gambar yang disimpan di disk public.

PHP
FileUpload::make('image')
    ->disk('public')
    ->directory('posts'),


Tags Input
Untuk field tags yang bertipe JSON, pastikan model Post sudah di-cast ke array.
+1

PHP
TagsInput::make('tags'),


Checkbox (Published)
Menambahkan opsi centang untuk status publikasi.

PHP
Checkbox::make('published'),


Date Picker (Published At)
Menambahkan form untuk memilih tanggal tayang.

PHP
DatePicker::make('published_at'),


<img width="926" height="414" alt="Screenshot 2026-04-06 194442" src="https://github.com/user-attachments/assets/6006e89c-173b-495a-8e14-bcf184d78b7d" />


C. Menampilkan Data di Tabel
Untuk memunculkan data yang sudah diinput pada tabel halaman index, buka file PostsTable.php dan tambahkan kolom berikut:

PHP
TextColumn::make('title'),
TextColumn::make('slug'),
TextColumn::make('category.name'),
ColorColumn::make('color'),
ImageColumn::make('image')
    ->disk('public'),


D. Konfigurasi Tambahan & Pengujian
Agar gambar bisa muncul dengan baik, jalankan perintah berikut untuk membuat symbolic link:

Bash
php artisan storage:link


Pastikan file .env memiliki konfigurasi APP_URL=http://localhost:8000.
+1

Lakukan pengujian dengan membuat minimal 1 Post, pilih kategori, upload gambar, centang published, dan klik Create.

<img width="1361" height="320" alt="Screenshot 2026-04-06 194726" src="https://github.com/user-attachments/assets/29342ae9-0a86-4df5-a157-745565a6aec9" />


<img width="984" height="596" alt="Screenshot 2026-04-06 200734" src="https://github.com/user-attachments/assets/2312e065-d2b0-48a4-9b7d-bb3b02ad579e" />


Analisis & Diskusi
1. Mengapa kita perlu storage:link? 

Secara default, Laravel menyimpan file hasil upload ke dalam direktori storage/app/public. Namun, direktori storage ini tidak dapat diakses langsung oleh publik (browser) demi alasan keamanan. Perintah php artisan storage:link berfungsi untuk membuat symbolic link (semacam shortcut) dari folder public/storage yang mengarah ke storage/app/public. Dengan begitu, file gambar yang diupload dapat diakses dan ditampilkan di halaman web.

2. Apa fungsi $casts untuk field JSON? 

Field seperti tags disimpan di dalam database MariaDB/MySQL sebagai tipe data teks berformat string JSON. Namun, di dalam aplikasi Laravel (khususnya komponen TagsInput pada Filament), data tersebut perlu dikelola sebagai array PHP agar bisa diolah dengan mudah. Fungsi $casts pada Model (protected $casts = ['tags' => 'array'];) digunakan agar Laravel secara otomatis mengubah string JSON menjadi Array saat data diambil dari database, dan mengubahnya kembali menjadi string JSON saat data disimpan.

3. Mengapa kita menggunakan category.name bukan category_id? 

category_id hanya menampilkan angka (Foreign Key) yang merujuk pada ID di tabel categories, sehingga tidak informatif bagi pengguna aplikasi (admin). Dengan menggunakan dot notation category.name, kita memanfaatkan relasi Eloquent untuk mengambil dan menampilkan kolom name dari tabel categories. Ini membuat data yang tampil di antarmuka tabel jauh lebih ramah pengguna karena admin dapat langsung membaca nama kategorinya (misal: "Laravel" atau "PHP").

4. Apa perbedaan RichEditor dan MarkdownEditor? 

RichEditor: Menyediakan antarmuka WYSIWYG (What You See Is What You Get). Pengguna dapat menebalkan teks, membuat list, atau mengatur layout menggunakan tombol-tombol visual seperti pada Microsoft Word. Output yang dihasilkan dan disimpan di database langsung berupa sintaks HTML.

MarkdownEditor: Menyediakan antarmuka berbasis teks di mana pengguna menggunakan sintaks Markdown (seperti **tebal**, # Heading) untuk memformat dokumen. Editor ini lebih ringan dan lebih disukai oleh kalangan developer. Output yang disimpan di database adalah teks Markdown murni, yang nantinya perlu di-parse (dikonversi) menjadi HTML saat ditampilkan ke publik.

Praktikum 6 - Custom Layout Form dengan Section & Group di Filament
A. Mengatur Layout Dasar dengan Columns
Secara default, field pada Filament tersusun vertikal. Untuk membuat tampilan lebih efisien, kita dapat menggunakan fungsi columns().

Buka file PostForm.php.

Tambahkan fungsi columns(3) atau jumlah kolom yang diinginkan pada skema komponen.

PHP
return $schema
    ->components([
        // Field akan terbagi menjadi 3 kolom
    ])->columns(3);
<img width="1319" height="650" alt="Screenshot 2026-04-08 073251" src="https://github.com/user-attachments/assets/cd1b040a-d89e-4af5-ae75-c2b39b2007ae" />


B. Menggunakan Section untuk Pengelompokan
Section digunakan untuk membungkus beberapa field ke dalam satu kotak (card) agar terlihat lebih terorganisir.

Gunakan Section::make() untuk mengelompokkan field utama.

Tambahkan description() dan icon() untuk memberikan informasi tambahan pada bagian tersebut.

PHP
Section::make('Main Content')
    ->description('Isi konten utama postingan Anda di sini.')
    ->icon('heroicon-o-document-text')
    ->schema([
        TextInput::make('title')->required(),
        TextInput::make('slug')->required(),
        MarkdownEditor::make('content')->columnSpanFull(),
    ])->columns(2),
C. Mengatur Proporsi dengan ColumnSpan
Agar layout lebih profesional, kita bisa mengatur lebar tiap field menggunakan columnSpan().

Gunakan columnSpan(1) atau columnSpan(2) untuk menentukan lebar relatif field di dalam grid.

Gunakan columnSpanFull() agar field (seperti Editor) mengambil seluruh lebar baris.

PHP
TextInput::make('title')->columnSpan(2),
ColorPicker::make('color')->columnSpan(1),
D. Menggunakan Group untuk Layout Kompleks
Group digunakan untuk mengatur tata letak tanpa memberikan tampilan visual (seperti garis atau kotak) tambahan. Ini berguna untuk menumpuk field secara vertikal di dalam kolom tertentu.

PHP
Group::make()->schema([
    Section::make('Status')
        ->schema([
            Checkbox::make('published'),
            DatePicker::make('published_at'),
        ]),
    Section::make('Image')
        ->schema([
            FileUpload::make('image'),
        ]),
]),
<img width="1326" height="666" alt="Screenshot 2026-04-08 080413" src="https://github.com/user-attachments/assets/5804391c-4077-4598-b783-391857204d9c" />


Analisis & Diskusi
1. Mengapa layout form penting dalam aplikasi admin?
Layout yang baik meningkatkan pengalaman pengguna (UX). Dalam panel admin dengan banyak field, layout yang teratur membantu admin menemukan field yang dicari dengan cepat, mengurangi kesalahan input, dan membuat tampilan aplikasi terlihat lebih profesional serta tidak mengintimidasi.

2. Apa perbedaan Section dan Group?

Section: Memiliki tampilan visual berupa kotak/card dengan border, judul, deskripsi, dan ikon. Digunakan untuk memisahkan logika konten secara jelas.

Group: Tidak memiliki tampilan visual. Hanya berfungsi sebagai pembungkus (container) secara logis di balik layar untuk memudahkan pengaturan kolom (grid) yang lebih kompleks.

3. Kapan kita menggunakan columnSpanFull()?
Kita menggunakan columnSpanFull() pada komponen yang membutuhkan ruang luas untuk input datanya, seperti MarkdownEditor, RichEditor, atau Textarea. Hal ini bertujuan agar pengguna lebih leluasa saat mengetik konten yang panjang tanpa terbatasi oleh kolom yang sempit.

4. Apa keuntungan sistem grid 12 kolom?
Sistem grid 12 kolom (yang juga diadaptasi Filament dari Tailwind CSS) memberikan fleksibilitas tinggi dalam pembagian layar. Karena angka 12 habis dibagi 2, 3, 4, dan 6, kita bisa dengan mudah mengatur proporsi lebar field (misal: 2/3 untuk konten utama dan 1/3 untuk sidebar) dengan sangat presisi.

Praktikum 6 - Implementasi Form Validation pada Filament
A. Konsep Dasar Validasi di Filament
Filament terintegrasi langsung dengan sistem validasi Laravel. Hal ini memungkinkan kita untuk menerapkan aturan validasi (rules) langsung pada komponen form di dalam file Resource. Validasi berfungsi untuk memastikan data yang masuk ke database sesuai dengan format dan ketentuan yang diinginkan.

B. Menerapkan Validasi Dasar (Required & Length)
Langkah awal adalah memastikan field penting tidak dikosongkan oleh user dan memiliki panjang karakter yang sesuai.

Buka file PostForm.php.

Tambahkan method required() untuk field yang wajib diisi.

Tambahkan minLength() atau maxLength() untuk membatasi jumlah karakter.

PHP
TextInput::make('title')
    ->required()
    ->minLength(5)
    ->maxLength(255),

TextInput::make('slug')
    ->required()
    ->minLength(3)
    ->unique(ignoreRecord: true),
C. Validasi Unique (Unik)
Validasi ini sangat penting untuk field seperti slug agar tidak ada URL yang sama di database.

Gunakan method unique().

Tambahkan parameter ignoreRecord: true agar saat kita melakukan Edit data, sistem tidak menganggap data tersebut duplikat dengan dirinya sendiri.

PHP
TextInput::make('slug')
    ->unique(Table: Post::class, column: 'slug', ignoreRecord: true),
D. Validasi File Upload & Relasi
Kita juga bisa memastikan bahwa user harus memilih kategori dan mengunggah gambar sebelum menyimpan data.

Pada field Select (Category), tambahkan required().

Pada field FileUpload, tambahkan required() dan batasan tipe file jika diperlukan.

PHP
Select::make('category_id')
    ->relationship('category', 'name')
    ->required(),

FileUpload::make('image')
    ->image()
    ->required(),
E. Custom Error Message
Jika ingin menampilkan pesan kesalahan dalam bahasa Indonesia atau kalimat tertentu, kita bisa menggunakan method validationMessages().

PHP
TextInput::make('title')
    ->required()
    ->validationMessages([
        'required' => 'Judul postingan tidak boleh dikosongkan.',
        'min' => 'Judul minimal harus berisi 5 karakter.',
    ]),
Analisis & Diskusi
1. Mengapa validasi penting pada admin panel?
Validasi adalah baris pertahanan pertama untuk menjaga integritas data. Tanpa validasi, user bisa memasukkan data yang rusak, kosong, atau duplikat yang dapat menyebabkan error pada aplikasi (misalnya error 404 pada slug yang sama atau error null pada field yang wajib ada).

2. Apa perbedaan validasi client-side dan server-side?

Client-side: Validasi yang terjadi di browser (misal atribut required pada HTML). Ini memberikan respon cepat ke user tapi mudah diakali.

Server-side: Validasi yang dilakukan oleh Laravel/Filament di server. Ini jauh lebih aman dan wajib ada karena tetap berjalan meskipun user mematikan fitur JavaScript di browser.

3. Mengapa unique otomatis bekerja saat edit data jika menggunakan ignoreRecord: true?
Secara default, aturan unique akan mengecek seluruh baris di tabel. Jika kita mengedit post ber-ID 1 dengan slug "berita-hari-ini", validasi akan mendeteksi bahwa slug tersebut sudah ada di database (milik ID 1 itu sendiri) dan memblokir penyimpanan. Dengan ignoreRecord: true, Filament memerintahkan Laravel untuk mengecualikan ID data yang sedang diedit dari pengecekan tersebut.

4. Kapan kita perlu menggunakan rules array dibanding shortcut method?
Shortcut method seperti ->required() atau ->numeric() lebih mudah dibaca dan cepat ditulis. Namun, jika kita memiliki aturan validasi yang sangat kompleks atau menggunakan custom logic (seperti menggunakan Rule::prohibitedIf(...)), maka menggunakan method ->rules(['required', 'max:255', ...]) dalam bentuk array akan lebih fleksibel.

[🔗 Repository GitHub ]
(https://github.com/Xyrfo/Pemograman-web-lanjut)
