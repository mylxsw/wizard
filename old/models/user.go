package models

import (
	"time"
)

type User struct {
	ID        uint       `orm:"column(id);pk;auto"`
	Username  string     `orm:"size(30)"`
	Email     string     `orm:"size(50)"`
	Password  string     `orm:"size(64)"`
	Projects  []*Project `orm:"reverse(many)"`
	CreatedAt time.Time
	UpdatedAt time.Time
}

func (u *User) TableName() string {
	return "users"
}
