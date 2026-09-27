# Dokumentasi API Pemweb2

**Base URL:** `http://127.0.0.1:8000/api`

**Header Wajib:**
- `Accept: application/json`
- `Content-Type: application/json` (untuk POST / PUT / PATCH)

---

## 1. Status API

| Method | URI | Deskripsi |
|---|---|---|
| GET | /status | Cek status API |

**Contoh Response:**
```json
{
  "sukses": true,
  "pesan": "API Pemweb II aktif",
  "waktu": "2026-09-24T03:33:47+00:00"
}
```

---

## 2. Mahasiswa

| Method | URI | Deskripsi |
|---|---|---|
| GET | /mahasiswa | List mahasiswa (pagination) |
| POST | /mahasiswa | Tambah mahasiswa |
| GET | /mahasiswa/{id} | Detail mahasiswa |
| PUT/PATCH | /mahasiswa/{id} | Update mahasiswa |
| DELETE | /mahasiswa/{id} | Hapus mahasiswa |
| GET | /program-studi/{id}/mahasiswa | List mahasiswa per prodi |

### Parameter GET /mahasiswa

| Parameter | Tipe | Deskripsi |
|---|---|---|
| cari | string | Cari nama atau NIM |
| angkatan | integer | Filter tahun angkatan |
| program_studi_id | integer | Filter program studi |
| urut | string | Kolom sorting: `nama`, `nim`, `angkatan`, `ipk` |
| arah | string | Arah sorting: `asc` atau `desc` |
| per_halaman | integer (1-100) | Jumlah data per halaman |
| fields | string | Kolom yang ditampilkan (comma-separated) |

### Contoh Body POST /mahasiswa

```json
{
  "program_studi_id": 1,
  "nim": "H1A999999",
  "nama": "Nama Mahasiswa",
  "email": "email@example.com",
  "angkatan": 2025,
  "ipk": 3.50
}
```

### Contoh Response GET /mahasiswa

```json
{
  "data": [
    {
      "id": 1,
      "nim": "H1A873543",
      "nama": "Bakiadi Prayoga",
      "email": "bernhard.janiya@example.com",
      "angkatan": 2023,
      "ipk": 3.9,
      "aktif": false,
      "program_studi": {
        "id": 3,
        "kode": "TE",
        "nama": "Teknik Elektro",
        "jenjang": "S1"
      }
    }
  ],
  "links": {
    "first": "http://127.0.0.1:8000/api/mahasiswa?page=1",
    "last": "http://127.0.0.1:8000/api/mahasiswa?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 5
  }
}
```

### Contoh Response GET /mahasiswa?fields=nim,nama,ipk

```json
{
  "data": [
    {
      "nim": "H1A427630",
      "nama": "Alambana Saptono S.Kom",
      "ipk": 3.6
    },
    {
      "nim": "H1A873543",
      "nama": "Bakiadi Prayoga",
      "ipk": 3.9
    }
  ]
}
```

### Contoh Response 422 (Validasi Gagal)

```json
{
  "message": "NIM tersebut sudah terdaftar (and 1 more error)",
  "errors": {
    "nim": ["NIM tersebut sudah terdaftar"],
    "email": ["The email has already been taken."]
  }
}
```

---

## 3. Matakuliah

| Method | URI | Deskripsi |
|---|---|---|
| GET | /matakuliah | List matakuliah |
| POST | /matakuliah | Tambah matakuliah |
| GET | /matakuliah/{id} | Detail matakuliah |
| PUT/PATCH | /matakuliah/{id} | Update matakuliah |
| DELETE | /matakuliah/{id} | Hapus matakuliah |

### Parameter GET /matakuliah

| Parameter | Tipe | Deskripsi |
|---|---|---|
| cari | string | Cari nama atau kode |
| semester | integer | Filter semester |
| urut | string | Kolom sorting: `kode`, `nama`, `sks`, `semester` |
| arah | string | Arah sorting: `asc` atau `desc` |
| per_halaman | integer (1-100) | Jumlah data per halaman |

### Contoh Body POST /matakuliah

```json
{
  "kode": "TK0501",
  "nama": "Jaringan Syaraf Tiruan",
  "sks": 3,
  "semester": 5
}
```

### Contoh Response GET /matakuliah

```json
{
  "data": [
    {
      "id": 1,
      "kode": "TK0101",
      "nama": "Pemrograman Dasar",
      "sks": 3,
      "semester": 1,
      "created_at": "2026-09-23T15:56:16+00:00",
      "updated_at": "2026-09-23T15:56:16+00:00"
    }
  ],
  "links": {...},
  "meta": {...}
}
```

---

## 4. Endpoint Per Program Studi

| Method | URI | Deskripsi |
|---|---|---|
| GET | /program-studi/{id}/mahasiswa | List mahasiswa pada prodi tertentu |

### Parameter GET /program-studi/{id}/mahasiswa

| Parameter | Tipe | Deskripsi |
|---|---|---|
| id | integer | ID program studi (path parameter) |
| per_halaman | integer (1-100) | Jumlah data per halaman |

### Contoh Response

```json
{
  "data": [
    {
      "id": 42,
      "nim": "H1A427630",
      "nama": "Alambana Saptono S.Kom",
      "angkatan": 2024,
      "ipk": 3.6,
      "program_studi": {
        "id": 1,
        "kode": "TK",
        "nama": "Teknik Komputer",
        "jenjang": "S1"
      }
    }
  ],
  "links": {...},
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 15
  }
}
```

---

## 5. Kode Status HTTP

| Kode | Arti |
|---|---|
| 200 | OK — permintaan berhasil |
| 201 | Created — data berhasil dibuat |
| 404 | Not Found — data tidak ditemukan |
| 422 | Unprocessable Entity — validasi gagal |
| 500 | Internal Server Error — error pada server |

---

## 6. Cara Pengujian di Postman

1. Jalankan server: `php artisan serve`
2. Buka Postman
3. Set method + URL (misal GET `http://127.0.0.1:8000/api/mahasiswa`)
4. Tambahkan header `Accept: application/json`
5. Untuk POST/PUT, tambahkan `Content-Type: application/json` dan body JSON
6. Klik **Send**

**Koleksi Postman:** tersedia di file `docs/pemweb2-api.json`