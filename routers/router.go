package routers

import (
	"github.com/astaxie/beego"
	"github.com/mylxsw/wizard/controllers"
)

func init() {

	// "/" - 首页
	// "/:id" - 项目首页
	// "/:id/setting" - 项目配置

	// "/:id/page" - 新增页面
	// "/:id/page/:page_id" - 项目下的页面查看(编辑)

	// "/auth/login" 登录
	// "/auth/register" 注册

	// "/user/password" 修改密码页面
	// "/user" 用户信息查看

	beego.Router("/", &controllers.MainController{})
	beego.Router("/:id([0-9]+)", &controllers.ProjectController{})
	beego.Router("/:id([0-9]+)/setting", &controllers.SettingController{})

	beego.Router("/:id([0-9]+)/page/?:page([0-9]+)", &controllers.PageController{})
}
