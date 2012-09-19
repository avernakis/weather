#!/bin/sh
# Create the SQLite3 databases; copy to /var/db and type: chmod a+x create_db.sh && ./create_db.sh
sqlite3 weather-hourly.db "create table hourly (stamp INTEGER PRIMARY KEY, direction TEXT, average REAL, gust REAL, humidity REAL, rainfall REAL, temperature REAL, feel REAL, relative REAL, absolute REAL, trend TEXT, prediction TEXT);"
sqlite3 weather-fifteen.db "create table fifteen (stamp INTEGER PRIMARY KEY, direction TEXT, average REAL, gust REAL, humidity REAL, rainfall REAL, temperature REAL, feel REAL, absolute REAL, relative REAL);"

