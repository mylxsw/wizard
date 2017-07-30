package database

import "time"

func ParseDateTimeStr(date string) time.Time {
	dateTime, err := time.Parse("2006-1-2 15:04:05+00:00", date)
	if err != nil {
		panic(err)
	}

	return dateTime
}
