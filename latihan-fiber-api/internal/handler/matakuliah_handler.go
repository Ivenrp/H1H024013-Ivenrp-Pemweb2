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

func DaftarMataKuliah(c fiber.Ctx) error {
	daftar, err := repository.AmbilMataKuliah()
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil mata kuliah")
	}
	return c.JSON(fiber.Map{"sukses": true, "data": daftar})
}
func DetailMataKuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilMataKuliahPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil mata kuliah")
	}
	return c.JSON(fiber.Map{"sukses": true, "data": m})
}
func BuatMataKuliah(c fiber.Ctx) error {
	var req PermintaanMataKuliah
	if err := c.Bind().Body(&req); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	req.Kode, req.Nama = strings.TrimSpace(req.Kode), strings.TrimSpace(req.Nama)
	if req.Kode == "" || req.Nama == "" || req.SKS < 1 || req.SKS > 6 || req.Semester < 1 || req.Semester > 14 {
		return fiber.NewError(fiber.StatusBadRequest, "Kode, nama, SKS (1-6), dan semester (1-14) wajib valid")
	}
	m := model.MataKuliah{Kode: req.Kode, Nama: req.Nama, SKS: req.SKS, Semester: req.Semester}
	if err := repository.SimpanMataKuliah(&m); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal menyimpan mata kuliah; pastikan kode unik")
	}
	return c.Status(fiber.StatusCreated).JSON(fiber.Map{"sukses": true, "pesan": "Mata kuliah berhasil dibuat", "data": m})
}
func PerbaruiMataKuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilMataKuliahPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil mata kuliah")
	}
	var req PermintaanMataKuliah
	if err := c.Bind().Body(&req); err != nil {
		return fiber.NewError(fiber.StatusBadRequest, "Format body tidak valid")
	}
	if req.Kode != "" {
		m.Kode = strings.TrimSpace(req.Kode)
	}
	if req.Nama != "" {
		m.Nama = strings.TrimSpace(req.Nama)
	}
	if req.SKS > 0 {
		m.SKS = req.SKS
	}
	if req.Semester > 0 {
		m.Semester = req.Semester
	}
	if m.SKS > 6 || m.Semester > 14 {
		return fiber.NewError(fiber.StatusBadRequest, "SKS maksimal 6 dan semester maksimal 14")
	}
	if err := repository.PerbaruiMataKuliah(&m); err != nil {
		return fiber.NewError(fiber.StatusUnprocessableEntity, "Gagal memperbarui mata kuliah")
	}
	return c.JSON(fiber.Map{"sukses": true, "pesan": "Mata kuliah berhasil diperbarui", "data": m})
}
func HapusMataKuliah(c fiber.Ctx) error {
	id, err := strconv.Atoi(c.Params("id"))
	if err != nil || id < 1 {
		return fiber.NewError(fiber.StatusBadRequest, "ID harus berupa angka positif")
	}
	m, err := repository.AmbilMataKuliahPerId(uint(id))
	if errors.Is(err, gorm.ErrRecordNotFound) {
		return fiber.NewError(fiber.StatusNotFound, "Mata kuliah tidak ditemukan")
	}
	if err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal mengambil mata kuliah")
	}
	if err := repository.HapusMataKuliah(&m); err != nil {
		return fiber.NewError(fiber.StatusInternalServerError, "Gagal menghapus mata kuliah")
	}
	return c.JSON(fiber.Map{"sukses": true, "pesan": "Mata kuliah berhasil dihapus"})
}
