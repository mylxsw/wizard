package controllers

import (
	"github.com/astaxie/beego"
)

type PageController struct {
	beego.Controller
}

func (c *PageController) Get() {
	c.TplName = "page.tpl"
}
