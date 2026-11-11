package router

import (
	"github.com/gofiber/fiber/v3"
	"latihan-fiber-api/internal/handler"
)

func Daftarkan(app *fiber.App) {
	api := app.Group("/api")
	api.Get("/status", func(c fiber.Ctx) error {
		return c.JSON(fiber.Map{"sukses": true, "pesan": "API Fiber aktif"})
	})

	mahasiswa := api.Group("/mahasiswa")
	mahasiswa.Get("/", handler.DaftarMahasiswa)
	mahasiswa.Post("/", handler.BuatMahasiswa)
	mahasiswa.Get("/:id", handler.DetailMahasiswa)
	mahasiswa.Put("/:id", handler.PerbaruiMahasiswa)
	mahasiswa.Delete("/:id", handler.HapusMahasiswa)
	mahasiswa.Post("/:id/pulihkan", handler.PulihkanMahasiswa)

	mataKuliah := api.Group("/matakuliah")
	mataKuliah.Get("/", handler.DaftarMataKuliah)
	mataKuliah.Post("/", handler.BuatMataKuliah)
	mataKuliah.Get("/:id", handler.DetailMataKuliah)
	mataKuliah.Put("/:id", handler.PerbaruiMataKuliah)
	mataKuliah.Delete("/:id", handler.HapusMataKuliah)

	api.Get("/program-studi", handler.DaftarProgramStudi)
}
