#!/bin/sh
# Weather Station Logger
# Logs data every 15 minutes to the SQLite database, and once an hour to the hourly.

if [ ! -d /root/weather/data ]; then
	exit
fi

python /root/pywws/Hourly.py -v /root/weather/data
echo "#!/bin/sh" > /var/db/raw/fifteen-insert.sqlite
cat < /var/db/raw/sqlite-fifteen.txt >> /var/db/raw/fifteen-insert.sqlite
chmod a+x /var/db/raw/fifteen-insert.sqlite
sh /var/db/raw/fifteen-insert.sqlite

echo "#!/bin/sh" > /var/db/raw/hourly-insert.sqlite
cat < /var/db/raw/sqlite-hourly.txt >> /var/db/raw/hourly-insert.sqlite
chmod a+x /var/db/raw/hourly-insert.sqlite
sh /var/db/raw/hourly-insert.sqlite
