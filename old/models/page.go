package models

import (
	"time"
)

type Page struct {
	ID          uint     `orm:"column(id);pk;auto"`
	Title       string   `orm:"size(255)"`
	Description string   `orm:"size(255);null"`
	Content     string   `orm:"type(text)"`
	Project     *Project `orm:"rel(fk)"`
	User        *User    `orm:"rel(fk)"`
	Type        int
	Status      int
	CreatedAt   time.Time
	UpdatedAt   time.Time
}

func (page *Page) TableName() string {
	return "pages"
}
