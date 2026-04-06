# Laporan Praktikum Pemrograman Web Lanjut

## Identitas Mahasiswa

| Keterangan | Data |
|------------|------|
| **Nama**   | Rifo Anggi Barbara Danuarta |
| **NIM**    | 244107020063 |
| **Kelas**  | TI-2F |

---

## Persiapan
**Laravel menggunakan MVC (Model, View, Controller)**

* **Model**  
  Berisi semua metode dan atribut yang diperlukan untuk berinteraksi dengan database.

* **View**  
  Digunakan untuk menampilkan data ke pengguna (Frontend).

* **Controller**  
  Menjadi penghubung antara Model dan View (Backend).

---

## Route
Route digunakan sebagai penghubung antara user dengan aplikasi (penentu URL).

---

## Praktikum 1
<details>
<summary>Detail</summary>
<img width="1365" height="726" alt="Screenshot 2026-03-22 145436" src="https://github.com/user-attachments/assets/32630a00-725a-442e-8888-f8e82c4b43b5" />

Halaman login yang dihasilkan Laravel filament

## Analisa
*1. Apa kelebihan Filament dibanding membuat admin panel manual?*

cepat dikembangkan, UI sudah siap pakai, terintegrasi dengan eloquent, fitur lengkap, lebih sedikit kode.

Filament mempercepat pembuatan admin panel, mengurangi kompleksitas coding, dan meningkatkan efisiensi development.

*2. Mengapa Filament menggunakan Livewire?*

tanpa js manual, realtime update, integritas laravel, lebih sederhana dibanding SPA

Livewire memungkinkan Filament membuat UI interaktif berbasis server-side tanpa kompleksitas frontend framework.

*3. Apa perbedaan SQLite dan MySQL dalam development?* 

🔹 SQLite
Database berbentuk file (.sqlite)
Tidak perlu server
Cocok untuk development / testing
🔹 MySQL
Database berbasis server
Butuh instalasi (XAMPP, dll)
Cocok untuk production

SQLite → untuk development cepat
MySQL → untuk aplikasi skala besar & production

*4. Apa fungsi Panel Builder?*

Membuat dashboard admin
Mengatur:
Navigation menu
Resource (CRUD)
Halaman (pages)
Mengelola akses panel
Custom tampilan admin

Panel Builder berfungsi sebagai alat untuk membangun dan mengelola seluruh struktur admin panel dalam Filament.

</details>

---

## Praktikum 2
<details>
<summary>Detail</summary>

<img width="1056" height="79" alt="Screenshot 2026-03-22 190102" src="https://github.com/user-attachments/assets/97dfa409-f30d-4958-93cf-a4571ff2aa43" />
<img width="1365" height="432" alt="Screenshot 2026-03-22 185948" src="https://github.com/user-attachments/assets/e39f1550-5d2f-409e-9925-e1785d47d542" />
<img width="1365" height="415" alt="Screenshot 2026-03-22 185928" src="https://github.com/user-attachments/assets/c5bd253b-ba89-46f8-b1a3-884efcd16b31" />
<img width="1365" height="367" alt="Screenshot 2026-03-22 185530" src="https://github.com/user-attachments/assets/64ac2370-aad7-4fb8-be8f-2f2465278aee" />
<img width="208" height="222" alt="Screenshot 2026-03-22 185249" src="https://github.com/user-attachments/assets/9a257a85-5bf7-4c07-94c9-e2f38a15eb06" />
<img width="1033" height="43" alt="Screenshot 2026-03-22 145657" src="https://github.com/user-attachments/assets/85742f43-6141-4423-a677-58df7bd91ac3" />
<img width="1365" height="723" alt="Screenshot 2026-03-22 145615" src="https://github.com/user-attachments/assets/0256fa04-1b2c-495a-94cd-f2a01496773f" />

## Analisis
*1. Mengapa Filament dapat membuat CRUD tanpa banyak coding?* 

a. Resource-based system

Kita hanya perlu membuat Resource, dan Filament otomatis generate:
Form (create & edit)
Table (list data)
Action (edit, delete)

b. Terintegrasi dengan Eloquent
Filament langsung membaca model Laravel, misalnya:
field database
relasi

Jadi tidak perlu:
query manual
controller panjang

c. Sudah menyediakan komponen siap pakai
Contoh:
TextInput
Select
DatePicker
Table Column

*2. Apa perbedaan Form Schema dan Table Schema?*

Form Schema
Digunakan untuk input data (Create & Edit)
Contoh:
TextInput (nama)
TextInput (email)
Password field

fokus: input user

Table Schema
Digunakan untuk menampilkan data
Contoh:
kolom nama
kolom email
tombol edit & delete

*3. Bagaimana jika kita ingin menambahkan validasi email unik?* 
TextInput::make('email')
    ->email()
    ->required()
    ->unique(ignoreRecord: true)
    
Penjelasan:
email() → valid format email
required() → wajib diisi
unique() → tidak boleh sama di database
ignoreRecord: true → supaya saat edit tidak dianggap duplikat


*4. Mengapa password tidak perlu kita hash manual?* 

use Illuminate\Support\Facades\Hash;

protected function password(): Attribute
{
    return Attribute::make(
        set: fn ($value) => Hash::make($value),
    );
}
Penjelasan:
Setiap password yang disimpan akan otomatis di-hash
Kita tidak perlu memanggil Hash::make() di controller

Kenapa harus di-hash?
agar password tidak tersimpan dalam bentuk asli
meningkatkan keamanan data user


</details>

---

## Praktikum 3
<details>
<summary>Detail</summary>

## Analisis 
*1. Mengapa kita perlu $fillable?* 

$fillable digunakan untuk menentukan field mana saja yang boleh diisi secara massal (mass assignment).

Fungsi utama:
Melindungi data dari serangan
Mencegah field sensitif ikut terisi otomatis

Tanpa $fillable
Semua input bisa masuk ke database, termasuk:
role
is_admin
ini berbahaya (security risk)

*2. Apa fungsi $casts pada Laravel?* 

$casts digunakan untuk mengubah tipe data dari database menjadi tipe tertentu secara otomatis.
Fungsi:
Mengubah data otomatis saat diambil dari database
Mempermudah penggunaan data

Contoh penggunaan:
datetime → langsung jadi objek tanggal
boolean → true / false

*3. Apa perbedaan integer biasa dengan foreign key?* 

Integer biasa
Hanya angka biasa
Tidak memiliki hubungan dengan tabel lain

Foreign Key
Field integer yang berelasi dengan tabel lain
Menghubungkan data antar tabel

*4. Bagaimana jika category dihapus tetapi masih ada post?* 

1. Restrict (default)

Tidak bisa hapus category
karena masih dipakai post

2. Cascade
onDelete('cascade')
Jika category dihapus:

semua post ikut terhapus
3. Set Null
onDelete('set null')

Jika category dihapus:
category_id pada post jadi NULL

</details>

---


🔗 **Repository GitHub**  
https://github.com/Xyrfo/Pemograman-web-lanjut
