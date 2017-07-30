package project

import (
	"github.com/astaxie/beego/orm"
	"github.com/mylxsw/wizard/models"
)

func All() []*models.Project {
	var projects []*models.Project

	o := orm.NewOrm()
	o.QueryTable("projects").RelatedSel().All(&projects)

	return projects
}

func Get(id uint) *models.Project {
	var project models.Project

	o := orm.NewOrm()
	err := o.QueryTable("projects").RelatedSel().Filter("id", id).One(&project)
	if err == orm.ErrNoRows {
		return nil
	}

	o.LoadRelated(&project, "Pages")

	return &project
}
