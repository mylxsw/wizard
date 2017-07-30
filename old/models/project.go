package models

import (
	"time"
)

type Project struct {
	ID          uint    `orm:"column(id);auto;pk"`
	Title       string  `orm:"size(255)"`
	Description string  `orm:"size(255);null"`
	User        *User   `orm:"rel(fk)"`
	Pages       []*Page `orm:"reverse(many)"`
	CreatedAt   time.Time
	UpdatedAt   time.Time
}

func (project *Project) TableName() string {
	return "projects"
}
