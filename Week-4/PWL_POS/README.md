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

<img width="436" height="159" alt="Screenshot 2026-03-06 211227" src="https://github.com/user-attachments/assets/1f2dc961-234c-473f-b9a6-f531972fb248" />

<img width="1229" height="329" alt="Screenshot 2026-03-06 211700" src="https://github.com/user-attachments/assets/8de121b0-4d68-49f3-8581-901ec4ba983d" />
terjadi error karena di dalam tabel wajid harus di isi kolom password jika tidak akan error

</details>

---

## Praktikum 2
<details>
<summary>Detail</summary>

## 2.1
<img width="469" height="279" alt="image" src="https://github.com/user-attachments/assets/232e7e3c-ee69-4b6a-bc97-7af67b47e1de" />
terjadi error karena data kosong dan view mencoba membaca data->user_id.

<img width="371" height="159" alt="image" src="https://github.com/user-attachments/assets/5b590b8b-a3d2-4031-893c-768445d28ea4" />
saya ubah user_id nya ke 2 soal nya use_id 1 di database saya tidak ada
jadi find ini mencari dengan menggunakan primary key

<img width="371" height="159" alt="image" src="https://github.com/user-attachments/assets/6ad329e2-9742-47db-9cbf-6de66e1bd4c6" />
hasil nya sama cuma beda nya ini menggambil data pertama

<img width="371" height="159" alt="image" src="https://github.com/user-attachments/assets/9a633e2b-9e7d-4903-87f6-2eb10019ccaa" />
sama aja beda nya ini menggambil data pertama berdasarkan kondisi 

<img width="363" height="135" alt="image" src="https://github.com/user-attachments/assets/cb8710ab-54c8-4a08-b797-7f72316ccc54" />
dan akan tampil hanya colom username ama nama aja karena yg di panggil username ama nama aja

<img width="417" height="258" alt="image" src="https://github.com/user-attachments/assets/083548df-e67a-4908-a9eb-23487178a00c" />
tidak akan error tetapi tampil 404 jika data tidak ada

## 2.2

<img width="410" height="152" alt="image" src="https://github.com/user-attachments/assets/1e206a17-5354-4db9-92ce-daf0188a8cbc" />
Jika data ditemukan maka akan dikembalikan sebagai object model.
Namun jika data tidak ditemukan maka Laravel akan melemparkan ModelNotFoundException yang akan menghasilkan error 404 (Not Found) pada halaman browser.

<img width="417" height="258" alt="Screenshot 2026-03-06 223956" src="https://github.com/user-attachments/assets/c019a902-38e2-4e3d-bd88-e2a77c373559" />
sama hasil nya akan 404 mencari manager9 dan tidak ada di colom 

## 2.3
<img width="337" height="47" alt="image" src="https://github.com/user-attachments/assets/9d1cd96e-1feb-4e2b-bcdb-649c46a16dd4" />
menampilkan jumlah data user dengan level_id = 2 dalam bentuk angka karena fungsi count() menghasilkan nilai integer.
dan tampil angka 2 yaitu 2 user yg memiliki level_id = 2 di database.

<img width="174" height="148" alt="image" src="https://github.com/user-attachments/assets/53e6a270-719e-4abf-a6a9-60c9259a4864" />
dengan menghapus dd(user) di user.controller
dan ubah user.blade di view
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data User</title>
</head>
<body>
    <h1>Data User</h1>
    <table border="1" cellpadding="2" cellspacing="0">
        <thead>
            <tr>
                <th>Jumlah Pengguna</th>
            </tr>
            <tr>
                <td>{{ $data }}</td>
            </tr>
    </table>


## 2.4

<img width="1209" height="325" alt="image" src="https://github.com/user-attachments/assets/2d767ff2-a2fc-48c6-a555-167dbbf54205" />
akan terjadi error karena kolom ada yg belum di isi

<img width="389" height="139" alt="image" src="https://github.com/user-attachments/assets/c3cd05e1-6588-4e51-9ac6-4584fa5826fa" />
<img width="980" height="89" alt="image" src="https://github.com/user-attachments/assets/f21b6f43-a753-497c-8ca4-28a29621df8f" />
Mencari data yang sesuai dengan kondisi
Jika data ditemukan maka akan digunakan
Jika tidak ditemukan maka akan membuat data baru

<img width="390" height="130" alt="Screenshot 2026-03-06 232153" src="https://github.com/user-attachments/assets/8a89e7c9-a61a-422e-a119-1fdb9e981c76" />
<img width="385" height="124" alt="image" src="https://github.com/user-attachments/assets/3106f763-a65f-4684-9edd-a385ba2e0952" />
<img width="990" height="96" alt="image" src="https://github.com/user-attachments/assets/a266d61a-24fd-4200-b4f1-66f73974cddd" />
hanya membuat object baru tetapi belum disimpan
jadi di database tidak ada 

<img width="387" height="140" alt="image" src="https://github.com/user-attachments/assets/2de80070-1639-4ddf-bc86-fdf19820bd86" />
<img width="982" height="111" alt="image" src="https://github.com/user-attachments/assets/d092d22c-e640-4c03-ac79-cf520ba7f03a" />
Data baru akan disimpan setelah memanggil method save().

## 2.5

<img width="357" height="132" alt="image" src="https://github.com/user-attachments/assets/9ca624ac-d19d-4358-a651-99da3b6fc7e1" />

<img width="405" height="39" alt="image" src="https://github.com/user-attachments/assets/311c273a-dfb4-4e82-9cfd-2e5c1bd81351" />

<img width="352" height="125" alt="image" src="https://github.com/user-attachments/assets/c39b5566-bddf-4c0c-8258-0d5a1a1602b9" />
<img width="368" height="39" alt="image" src="https://github.com/user-attachments/assets/6a9ae96e-df0f-48ba-a03e-776f6f438b23" />

</details>

---

## Project POS


🔗 **Repository GitHub**  
https://github.com/Xyrfo/Pemograman-web-lanjut
