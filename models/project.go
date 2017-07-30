package models

import (
	"time"
)

type Project struct {
	ID          uint
	Title       string
	Description string
	User        *User
	Pages       []*Page
	CreatedAt   time.Time
	UpdatedAt   time.Time
}

