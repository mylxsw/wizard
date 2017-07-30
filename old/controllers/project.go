package controllers

import (
	"fmt"

	"github.com/mylxsw/wizard/models"
	"github.com/mylxsw/wizard/models/page"
	"github.com/mylxsw/wizard/models/project"
)

type ProjectController struct {
	Controller
}

type LeftNavbar struct {
	ID    string
	Title string
	URL   string
}

func createProjectNavbars(projectID int, pages []*models.Page) []LeftNavbar {
	navbars := []LeftNavbar{}
	for _, p := range pages {
		navbars = append(navbars, LeftNavbar{
			ID:    string(p.ID),
			Title: p.Title,
			URL:   fmt.Sprintf("/%d?page=%d", projectID, p.ID),
		})
	}

	return navbars
}

func (c *ProjectController) Get() {
	projectID := c.PathParamInt("id")
	pageID := c.InputValueInt("page", 0)

	// query project
	proj := project.Get(uint(projectID))
	if proj == nil {
		c.Abort("404")
	}
	c.Assign("project", proj)

	if pageID == 0 && len(proj.Pages) > 0 {
		pageID = int(proj.Pages[0].ID)
	}

	// query page
	if pageID > 0 {
		p := page.Get(uint(pageID))
		if p == nil {
			c.Abort("404")
		}
		c.Assign("page", p)
	}

	c.Assign("params", map[string]interface{}{
		"page":       pageID,
		"project_id": projectID,
	})

	c.Assign("navbars", createProjectNavbars(projectID, proj.Pages)).Assign("current_navbar", string(pageID))

	c.DisplayView("page")
}
