package controllers

import (
	"strconv"

	"github.com/astaxie/beego"
)

// Controller 是所有控制器应该嵌入的类型
type Controller struct {
	beego.Controller
}

// DisplayView 用于视图渲染
func (c *Controller) DisplayView(tplName string) *Controller {
	c.TplName = tplName + ".tpl"
	c.Render()

	return c
}

// Assign 用于传递kv到视图层
func (c *Controller) Assign(key, value interface{}) *Controller {
	c.Data[key] = value
	return c
}

// AssignMulti 传递多组kv到视图层
func (c *Controller) AssignMulti(kvs map[interface{}]interface{}) *Controller {
	for key, value := range kvs {
		c.Assign(key, value)
	}

	return c
}

// PathParam 字符串形式返回路径中的参数
func (c *Controller) PathParam(key string) string {
	return c.Ctx.Input.Params()[":"+key]
}

// PathParamInt 以int形式返回路径中的参数
func (c *Controller) PathParamInt(key string) int {
	val, _ := strconv.Atoi(c.PathParam(key))
	return val
}

func (c *Controller) InputValue(key string, def ...string) string {
	return c.GetString(key, def...)
}

func (c *Controller) InputValueInt(key string, def int) int {
	val, _ := strconv.Atoi(c.InputValue(key, string(def)))
	return val
}
