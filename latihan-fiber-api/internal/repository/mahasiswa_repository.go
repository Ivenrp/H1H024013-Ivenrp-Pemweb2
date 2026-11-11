package repository

import (
	"gorm.io/gorm"
	"latihan-fiber-api/internal/config"
	"latihan-fiber-api/internal/model"
)

type FilterMahasiswa struct {
	Cari       string
	Angkatan   int
	Halaman    int
	PerHalaman int
}

func Ambil(filter FilterMahasiswa) ([]model.Mahasiswa, int64, error) {
	var daftar []model.Mahasiswa
	var total int64
	kueri := config.DB.Model(&model.Mahasiswa{})
	if filter.Cari != "" {
		pola := "%" + filter.Cari + "%"
		kueri = kueri.Where("nama LIKE ? OR nim LIKE ?", pola, pola)
	}
	if filter.Angkatan > 0 {
		kueri = kueri.Where("angkatan = ?", filter.Angkatan)
	}
	if err := kueri.Count(&total).Error; err != nil {
		return nil, 0, err
	}
	lompat := (filter.Halaman - 1) * filter.PerHalaman
	err := kueri.Preload("ProgramStudi").Order("nama ASC").Limit(filter.PerHalaman).Offset(lompat).Find(&daftar).Error
	return daftar, total, err
}

func AmbilPerId(id uint) (model.Mahasiswa, error) {
	var m model.Mahasiswa
	err := config.DB.Preload("ProgramStudi").First(&m, id).Error
	return m, err
}
func Simpan(m *model.Mahasiswa) error   { return config.DB.Create(m).Error }
func Perbarui(m *model.Mahasiswa) error { return config.DB.Save(m).Error }
func Hapus(m *model.Mahasiswa) error    { return config.DB.Delete(m).Error }
func Pulihkan(id uint) error {
	hasil := config.DB.Unscoped().Model(&model.Mahasiswa{}).Where("id = ?", id).Update("deleted_at", nil)
	if hasil.Error != nil {
		return hasil.Error
	}
	if hasil.RowsAffected == 0 {
		return gorm.ErrRecordNotFound
	}
	return nil
}
func AmbilProgramStudi() ([]map[string]interface{}, error) {
	var hasil []map[string]interface{}
	err := config.DB.Model(&model.ProgramStudi{}).
		Select("program_studis.id, program_studis.kode, program_studis.nama, program_studis.jenjang, COUNT(mahasiswas.id) AS jumlah_mahasiswa").
		Joins("LEFT JOIN mahasiswas ON mahasiswas.program_studi_id = program_studis.id AND mahasiswas.deleted_at IS NULL").
		Group("program_studis.id").Order("program_studis.nama ASC").Scan(&hasil).Error
	return hasil, err
}

func AmbilMataKuliah() ([]model.MataKuliah, error) {
	var daftar []model.MataKuliah
	err := config.DB.Order("semester ASC, nama ASC").Find(&daftar).Error
	return daftar, err
}
func AmbilMataKuliahPerId(id uint) (model.MataKuliah, error) {
	var m model.MataKuliah
	err := config.DB.First(&m, id).Error
	return m, err
}
func SimpanMataKuliah(m *model.MataKuliah) error   { return config.DB.Create(m).Error }
func PerbaruiMataKuliah(m *model.MataKuliah) error { return config.DB.Save(m).Error }
func HapusMataKuliah(m *model.MataKuliah) error    { return config.DB.Delete(m).Error }
