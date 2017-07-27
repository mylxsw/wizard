package routers

import (
	"github.com/astaxie/beego"
	"github.com/mylxsw/wizard/controllers"
)

func init() {
	beego.Router("/", &controllers.MainController{})
	beego.Router("/:id([0-9]+)", &controllers.PageController{})
	beego.Router("/page/:id([0-9]+)", &controllers.EditController{})
	beego.Router("/page/:id([0-9]+)/setting", &controllers.SettingController{})
}
