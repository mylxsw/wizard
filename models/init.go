package models

import "github.com/astaxie/beego/orm"

func RegisterModels() {
	orm.RegisterModel(new(Project), new(User), new(Page))
}
