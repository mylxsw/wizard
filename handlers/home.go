package handlers

import (
	"github.com/kataras/iris/context"
	"github.com/mylxsw/wizard/models/project"
)

type Project struct {
	ID          uint
	Title       string
	Description string
	AuthorID    uint
	Author      string
}

func HomeHandler(ctx context.Context) {
	projects := []Project{}

	for _, proj := range project.All() {
		projects = append(projects, Project{
			ID:          proj.ID,
			Title:       proj.Title,
			Description: proj.Description,
			Author:      proj.User.Username,
			AuthorID:    proj.User.ID,
		})
	}

	ctx.ViewData("projects", projects)
	ctx.View("index.html")
}