package main

import (
	"strings"

	"github.com/astaxie/beego"
	"github.com/astaxie/beego/context"
	"github.com/astaxie/beego/orm"
	_ "github.com/mattn/go-sqlite3"
	"github.com/mylxsw/wizard/models"
	_ "github.com/mylxsw/wizard/routers"
)

func main() {
	// 增加对Restful请求方法的支持
	beego.InsertFilter("*", beego.BeforeRouter, func(ctx *context.Context) {
		if ctx.Input.Query("_method") != "" && ctx.Input.IsPost() {
			ctx.Request.Method = strings.ToUpper(ctx.Input.Query("_method"))
		}
	})

	orm.RegisterDataBase("default", "sqlite3", "data.db")
	models.RegisterModels()
	orm.RunSyncdb("default", false, true)

	orm.Debug = true

	beego.Run()
}
