package model

import (
	"gorm.io/gorm"
	"time"
)

type ProgramStudi struct {
	ID        uint        `gorm:"primaryKey" json:"id"`
	Kode      string      `gorm:"size:10;uniqueIndex;not null" json:"kode"`
	Nama      string      `gorm:"size:100;not null" json:"nama"`
	Jenjang   string      `gorm:"size:10" json:"jenjang"`
	Mahasiswa []Mahasiswa `gorm:"foreignKey:ProgramStudiID" json:"-"`
	CreatedAt time.Time   `json:"dibuat_pada"`
	UpdatedAt time.Time   `json:"diubah_pada"`
}

type Mahasiswa struct {
	ID             uint           `gorm:"primaryKey" json:"id"`
	ProgramStudiID uint           `gorm:"index;not null" json:"program_studi_id"`
	ProgramStudi   *ProgramStudi  `gorm:"foreignKey:ProgramStudiID" json:"program_studi,omitempty"`
	Nim            string         `gorm:"size:20;uniqueIndex;not null" json:"nim"`
	Nama           string         `gorm:"size:100;not null" json:"nama"`
	Email          string         `gorm:"size:100;uniqueIndex;not null" json:"email"`
	Angkatan       int            `json:"angkatan"`
	Ipk            float64        `gorm:"type:decimal(4,2)" json:"ipk"`
	Aktif          bool           `gorm:"default:true" json:"aktif"`
	CreatedAt      time.Time      `json:"dibuat_pada"`
	UpdatedAt      time.Time      `json:"diubah_pada"`
	DeletedAt      gorm.DeletedAt `gorm:"index" json:"-"`
}

type MataKuliah struct {
	ID        uint      `gorm:"primaryKey" json:"id"`
	Kode      string    `gorm:"size:20;uniqueIndex;not null" json:"kode"`
	Nama      string    `gorm:"size:100;not null" json:"nama"`
	SKS       int       `gorm:"not null" json:"sks"`
	Semester  int       `gorm:"not null" json:"semester"`
	CreatedAt time.Time `json:"dibuat_pada"`
	UpdatedAt time.Time `json:"diubah_pada"`
}
