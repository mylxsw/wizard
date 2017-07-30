package controllers

import "github.com/mylxsw/wizard/models/project"

type MainController struct {
	Controller
}

type Project struct {
	ID          uint
	Title       string
	Description string
	AuthorID    uint
	Author      string
}

func (c *MainController) Get() {
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

	c.Assign("projects", projects).DisplayView("index")
}
