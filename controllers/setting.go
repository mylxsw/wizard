package controllers

import (
	"github.com/astaxie/beego"
)

type SettingController struct {
	beego.Controller
}

func (c *SettingController) Get() {
	c.TplName = "setting.tpl"
}
