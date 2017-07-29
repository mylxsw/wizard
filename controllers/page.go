package controllers

import (
	"fmt"

	"github.com/mylxsw/wizard/models/page"
	"github.com/mylxsw/wizard/models/project"
)

// PageController 文档页面控制器
type PageController struct {
	Controller
}

// Get 文档编辑、新增页面
func (c *PageController) Get() {

	projectID := c.PathParamInt("id")
	pageID := c.PathParamInt("page")

	if projectID <= 0 {
		c.Abort("404")
	}

	// query project
	proj := project.Get(uint(projectID))
	if proj == nil {
		c.Abort("404")
	}
	c.Assign("project", proj)

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
		"new":        pageID == 0,
	})

	c.DisplayView("edit")
}

// Post 新增文档
func (c *PageController) Post() {
	projectID := c.InputValueInt("project_id", 0)
	title := c.InputValue("title")
	content := c.InputValue("content")

	p := page.New(1, uint(projectID), title, content)
	if p != nil {
		c.Redirect(fmt.Sprintf("/%d/page/%d", projectID, p.ID), 302)
	}

	c.Abort("422")
}

// Put 更新文档
func (c *PageController) Put() {
	fmt.Println("put")
}
