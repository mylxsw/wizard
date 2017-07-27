package controllers

import (
	"github.com/astaxie/beego"
)

type EditController struct {
	beego.Controller
}

func (c *EditController) Get() {

	c.Data["title"] = "测试标题"
	c.Data["content"] = `
# 第一次拍星轨

第一次拍星轨，大约半个小时的时间，使用Sony A7的星轨插件拍摄，50张合成，效果略显粗糙，不过作为第一次尝试，也算是没有白白挨冻了。

![22535600](https://oayrssjpa.qnssl.com/2017-02-27-22535600.gif?imageView2/2/w/600/h/1000/interlace/0/q/100)


![2017-02-13 20_14_55](https://oayrssjpa.qnssl.com/2017-02-27-2017-02-13 20_14_55.gif?imageView2/2/w/600/h/1000/interlace/0/q/100)
`

	c.TplName = "edit.tpl"
	c.Render()
}
