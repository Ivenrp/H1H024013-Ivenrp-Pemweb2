package handler

import (
	"errors"
	"strconv"
	"strings"

	"github.com/gofiber/fiber/v3"
	"gorm.io/gorm"
	"latihan-fiber-api/internal/model"
	"latihan-fiber-api/internal/repository"
)

func DaftarMahasiswa(c fiber.Ctx) error {
	halaman, _ := strconv.Atoi(c.Query("halaman", "1"))
	perHalaman, _ := strconv.Atoi(c.Query("per_halaman", "10"))
	angkatan, _ := strconv.Atoi(c.Query("angkatan", "0"))
	if halaman < 1 {
		halaman = 1
	}
	if perHalaman < 1 || perHalaman > 100 {
		perHalaman = 10
	}
	daftar, total, err := repository.Ambil(repository.FilterMahasiswa{
		Cari: c.Query("cari", ""), Angkatan: angkatan, Halaman: halaman, PerHalaman: perHalaman,
	})
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data")
	}
	totalHalaman := int(total) / perHalaman
	if int(total)%perHalaman != 0 {
		totalHalaman++
	}
	return c.JSON(fiber.Map{"sukses": true, "data": daftar, "meta": fiber.Map{
		"halaman": halaman, "per_halaman": perHalaman, "total": total, "total_halaman": totalHalaman,
	}})
}

func DetailMahasiswa(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mahasiswa tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data")
	}
	return c.JSON(fiber.Map{"sukses": true, "data": m})
}

func BuatMahasiswa(c fiber.Ctx) error {
	var req PermintaanMahasiswa
	if err := c.Bind().Body(&req); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	req.Nim, req.Nama, req.Email = strings.TrimSpace(req.Nim), strings.TrimSpace(req.Nama), strings.TrimSpace(req.Email)
	if req.ProgramStudiID == 0 || req.Nim == "" || req.Nama == "" || req.Email == "" || req.Angkatan < 1900 || req.Ipk < 0 || req.Ipk > 4 {
		return fiber.NewError(fiber.StatusBadRequest, "Data mahasiswa tidak lengkap atau tidak valid")
	}
	aktif := true
	if req.Aktif != nil {
		aktif = *req.Aktif
	}
	m := model.Mahasiswa{ProgramStudiID: req.ProgramStudiID, Nim: req.Nim, Nama: req.Nama, Email: req.Email, Angkatan: req.Angkatan, Ipk: req.Ipk, Aktif: aktif}
	if err := repository.Simpan(&m); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal menyimpan data, periksa program studi, NIM, dan email")
	}
	return c.Status(fiber.StatusCreated).JSON(fiber.Map{"sukses": true, "pesan": "Data mahasiswa berhasil dibuat", "data": m})
}

func PerbaruiMahasiswa(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mahasiswa tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data")
	}
	var req PermintaanMahasiswa
	if err := c.Bind().Body(&req); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	if req.Nim != "" {
		m.Nim = strings.TrimSpace(req.Nim)
	}
	if req.Nama != "" {
		m.Nama = strings.TrimSpace(req.Nama)
	}
	if req.Email != "" {
		m.Email = strings.TrimSpace(req.Email)
	}
	if req.Angkatan > 0 {
		m.Angkatan = req.Angkatan
	}
	if req.Ipk >= 0 && req.Ipk <= 4 && req.Ipk != 0 {
		m.Ipk = req.Ipk
	}
	if req.ProgramStudiID > 0 {
		m.ProgramStudiID = req.ProgramStudiID
	}
	if req.Aktif != nil {
		m.Aktif = *req.Aktif
	}
	if err := repository.Perbarui(&m); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal memperbarui data")
	}
	return c.JSON(fiber.Map{"sukses": true, "pesan": "Data mahasiswa berhasil diperbarui", "data": m})
}

func HapusMahasiswa(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mahasiswa tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil data")
	}
	if err := repository.Hapus(&m); err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal menghapus data")
	}
	return c.JSON(fiber.Map{"sukses": true, "pesan": "Mahasiswa dipindahkan ke data terhapus"})
}

func PulihkanMahasiswa(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	if err := repository.Pulihkan(uint(id)); errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Data mahasiswa tidak ditemukan atau tidak sedang terhapus")
	} else if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal memulihkan data")
	}
	return c.JSON(fiber.Map{"sukses": true, "pesan": "Permintaan pemulihan diproses; data aktif kembali jika ID ditemukan"})
}

func DaftarProgramStudi(c fiber.Ctx) error {
	daftar, err := repository.AmbilProgramStudi()
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil program studi")
	}
	return c.JSON(fiber.Map{"sukses": true, "data": daftar})
}
