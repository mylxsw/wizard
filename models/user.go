package models

import (
	"time"
)

type User struct {
	ID        uint
	Username  string
	Email     string
	Password  string
	Projects  []*Project
	CreatedAt time.Time
	UpdatedAt time.Time
}
