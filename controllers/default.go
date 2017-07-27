package controllers

import (
	"github.com/astaxie/beego"
)

type MainController struct {
	beego.Controller
}

type Project struct {
	ID          uint
	Title       string
	Description string
	AuthorID    uint
	Author      string
}

func (c *MainController) Get() {
	projects := []Project{}

	projects = append(projects, Project{
		ID:    1,
		Title: "金融平台",
	})
	projects = append(projects, Project{
		ID:    1,
		Title: "数据保全服务",
	})
	projects = append(projects, Project{
		ID:    1,
		Title: "用户中心服务",
	})

	c.Data["projects"] = projects

	c.TplName = "index.tpl"
	c.Render()
}
