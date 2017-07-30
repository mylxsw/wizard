package handlers

import (
	"github.com/kataras/iris/context"
	"fmt"
	"github.com/mylxsw/wizard/models"
	"github.com/mylxsw/wizard/old/models/page"
	"github.com/mylxsw/wizard/models/project"
)

func ProjectHandler(ctx context.Context) {
	ctx.View("index.html")
}

func ProjectSettingHandler(ctx context.Context) {

	projectID, _ := ctx.Params().GetInt("id")
	pageID, _ := ctx.URLParamInt("page")

	// query project
	proj := project.Get(uint(projectID))
	if proj == nil {
		ctx.StatusCode(404)
		return
	}

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

	ctx.ViewData("project", proj)

	ctx.View("setting.html")
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