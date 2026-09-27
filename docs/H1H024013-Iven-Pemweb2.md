# Dokumentasi API Pemweb2

**Base URL:** `http://127.0.0.1:8000/api`

**Header Wajib:**
- `Accept: application/json`
- `Content-Type: application/json` (untuk POST/PUT/PATCH)

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