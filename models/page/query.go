package page

import (
	"fmt"
	"time"

	"github.com/astaxie/beego/orm"
	"github.com/mylxsw/wizard/models"
)

// Get 使用ID查询单个页面
func Get(id uint) *models.Page {
	page := models.Page{ID: id}

	o := orm.NewOrm()
	err := o.Read(&page, "id")

	if err != nil {
		return nil
	}

	return &page
}

func New(userID, projectID uint, title, content string) *models.Page {
	var page models.Page

	page.Project = &models.Project{
		ID: projectID,
	}
	page.User = &models.User{
		ID: userID,
	}
	page.Title = title
	page.Content = content
	page.CreatedAt = time.Now()
	page.UpdatedAt = time.Now()

	o := orm.NewOrm()
	_, err := o.Insert(&page)
	if err != nil {
		fmt.Println(err)
		return nil
	}

	return &page
}
