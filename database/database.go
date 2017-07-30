package database

import (
	"database/sql"
	"log"
)

type Connection struct {
	db *sql.DB
}

var connection *Connection

func CreateConnection(driverName, dataSourceName string) *Connection {
	connection = &Connection {}
	db, err := sql.Open(driverName, dataSourceName)
	if err != nil {
		log.Fatalf("连接数据库失败: %v", err)
	}

	connection.db = db

	return connection
}

func GetConnection() *Connection {
	return connection
}

func (conn *Connection) DB() *sql.DB {
	return conn.db
}