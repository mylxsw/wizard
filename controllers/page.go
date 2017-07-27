package controllers

import (
	"github.com/astaxie/beego"
)

type PageController struct {
	beego.Controller
}

type LeftNavbar struct {
	ID    string
	Title string
	URL   string
}

func (c *PageController) Get() {

	navbars := []LeftNavbar{}
	navbars = append(navbars, LeftNavbar{
		ID:    "1",
		Title: "约定规则",
		URL:   "/1",
	})
	navbars = append(navbars, LeftNavbar{
		ID:    "2",
		Title: "用户登录",
		URL:   "/1",
	})
	navbars = append(navbars, LeftNavbar{
		ID:    "3",
		Title: "错误状态吗",
		URL:   "/1",
	})

	c.Data["navbars"] = navbars
	c.Data["current_navbar"] = "2"

	c.Data["title"] = "测试API"
	c.Data["content"] = "测试内容。。。"

	c.TplName = "page.tpl"
	c.Render()
}
