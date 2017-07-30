package main

import (
	"github.com/kataras/iris"
	"github.com/kataras/iris/context"
	"github.com/kataras/iris/view"
	"github.com/mylxsw/wizard/handlers"

	_ "github.com/mattn/go-sqlite3"
	"github.com/mylxsw/wizard/database"
	"github.com/sirupsen/logrus"
)

func main() {

	logrus.SetLevel(logrus.DebugLevel)

	app := iris.New()

	app.OnErrorCode(iris.StatusInternalServerError, func(ctx context.Context) {
		errMessage := ctx.Values().GetString("error")
		if errMessage != "" {
			ctx.Writef("Internal server error: %s", errMessage)
			return
		}

		ctx.Writef("(Unexpected) internal server error")
	})

	app.Use(func(ctx context.Context) {
		ctx.Application().Logger().Infof("Begin request for path: %s", ctx.Path())
		ctx.Next()
	})

	app.StaticWeb("/static", "./static")

	viewEngine := view.HTML("./views", ".html").
		Reload(true).
		Layout("layout/default.html")
	app.RegisterView(viewEngine)

	// 首页
	app.Get("/", handlers.HomeHandler)
	// 项目页面，用于查API
	app.Get("/{id:int min(1)}", handlers.ProjectHandler)

	authRoute := app.Party("/")
	{
		// 项目配置页面
		authRoute.Get("/{id:int min(1)/setting", handlers.ProjectSettingHandler)

		// 页面编辑页面
		authRoute.Get("/{id:int min(1)}/page/{page:int min(1)}", handlers.PageEditHandler)

	}

	database.CreateConnection("sqlite3", "./data.db")
	app.Run(iris.Addr(":8080"), iris.WithCharset("UTF-8"))
}
