package controllers

import (
	"github.com/astaxie/beego"
)

type SettingController struct {
	beego.Controller
}

func (c *SettingController) Get() {

	c.Data["title"] = "测试项目"
	c.Data["description"] = "这是一个测试项目哦哦哦"

	c.TplName = "setting.tpl"
	c.Render()
}
