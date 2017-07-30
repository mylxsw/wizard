package handlers

import "github.com/kataras/iris/context"

func PageEditHandler(ctx context.Context) {
	ctx.View("edit.html")
}