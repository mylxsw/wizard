package controllers

type SettingController struct {
	Controller
}

func (c *SettingController) Get() {

	c.Assign("title", "测试项目").
		Assign("description", "这是一个测试项目哦哦哦").
		DisplayView("setting")
}
