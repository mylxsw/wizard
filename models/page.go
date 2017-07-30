package models

import (
	"time"
)

type Page struct {
	ID          uint
	Title       string
	Description string
	Content     string
	Project     *Project
	User        *User
	Type        int
	Status      int
	CreatedAt   time.Time
	UpdatedAt   time.Time
}
