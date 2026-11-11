package config

import (
	"latihan-fiber-api/internal/model"
	"log"
)

func Migrasi() {
	if err := DB.AutoMigrate(&model.ProgramStudi{}, &model.Mahasiswa{}, &model.MataKuliah{}); err != nil {
		log.Fatal("Gagal menjalankan migrasi: ", err)
	}
	log.Println("Migrasi selesai")
}

func DataAwal() {
	var jumlah int64
	if err := DB.Model(&model.ProgramStudi{}).Count(&jumlah).Error; err != nil {
		log.Fatal("Gagal memeriksa data program studi: ", err)
	}
	if jumlah == 0 {
		daftar := []model.ProgramStudi{
			{Kode: "TK", Nama: "Teknik Komputer", Jenjang: "S1"},
			{Kode: "IF", Nama: "Informatika", Jenjang: "S1"},
			{Kode: "TE", Nama: "Teknik Elektro", Jenjang: "S1"},
		}
		if err := DB.Create(&daftar).Error; err != nil {
			log.Fatal("Gagal membuat data awal program studi: ", err)
		}
		log.Println("Data awal program studi dibuat")
	}
}
