package controllers

import (
	"github.com/astaxie/beego"
)

type EditController struct {
	beego.Controller
}

func (c *EditController) Get() {
	c.TplName = "edit.tpl"
}
