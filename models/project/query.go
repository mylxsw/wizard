package project

import (
	"github.com/mylxsw/wizard/models"
	"github.com/mylxsw/wizard/database"
	log "github.com/sirupsen/logrus"
)

var sqlQueryAll = `
SELECT
    projects.id,
    projects.title,
    projects.description,
    projects.user_id,
    projects.created_at,
    projects.updated_at,
    users.username AS user_username,
    users.email AS user_email,
    users.created_at AS user_created_at,
    users.updated_at AS user_updated_at
FROM projects
    LEFT JOIN users ON users.id = projects.user_id`

func All() []*models.Project {
	conn := database.GetConnection().DB()

	rows, err := conn.Query(sqlQueryAll)
	if err != nil {
		log.Errorf("sql query failed: %v", err)
		return nil
	}

	defer rows.Close()

	projects := []*models.Project{}
	for rows.Next() {
		proj := models.Project{}
		user := models.User{}

		var projCreatedAt, projUpdatedAt, userCreatedAt, userUpdatedAt string

		rows.Scan(
			&proj.ID,
			&proj.Title,
			&proj.Description,
			&user.ID,
			&projCreatedAt,
			&projUpdatedAt,
			&user.Username,
			&user.Email,
			&userCreatedAt,
			&userUpdatedAt,
		)

		proj.CreatedAt = database.ParseDateTimeStr(projCreatedAt)
		proj.UpdatedAt = database.ParseDateTimeStr(projUpdatedAt)

		user.CreatedAt = database.ParseDateTimeStr(userCreatedAt)
		user.UpdatedAt = database.ParseDateTimeStr(userUpdatedAt)
		proj.User = &user

		projects = append(projects, &proj)
	}

	return projects
}


