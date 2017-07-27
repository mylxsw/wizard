package routers

import (
	"github.com/astaxie/beego"
	"github.com/mylxsw/wizard/controllers"
)

func init() {
	beego.Router("/", &controllers.MainController{})
	beego.Router("/page", &controllers.PageController{})
	beego.Router("/edit", &controllers.EditController{})
	beego.Router("/setting", &controllers.SettingController{})
}
