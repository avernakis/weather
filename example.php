<?php
	date_default_timezone_set("UTC");

	class HourlyDB extends SQLite3
	{
		function __construct() { $this->open('/var/db/weather-hourly.db'); }
	}
	class FifteenDB extends SQLite3
	{
		function __construct() { $this->open('/var/db/weather-fifteen.db'); }
	}

	$_database = new FifteenDB();
	$_query = $_database->query("SELECT * FROM fifteen ORDER BY stamp DESC LIMIT 1");
	$_result = $_query->fetchArray(SQLITE3_ASSOC);
	$_database->close();

	$_temperature = $_result["temperature"];
	$_feels_like  = $_result["feel"];

	$_time = "00:00";
	$_hrs=substr($_result["stamp"],8,2);
	$_min=substr($_result["stamp"],10,2);
	if($_hrs<12) { $_append = " AM"; } else { $_append = ""; }
	$_time = $_hrs . ":" . $_min . $_append;

	$_dd=substr($_result["stamp"],6,2);
	$_mm=substr($_result["stamp"],4,2);
	$_yyyy=substr($_result["stamp"],0,4);
	$_std_date = $_yyyy . "-" . $_mm . "-" . $_dd;
	$_day = date("l", strtotime($_std_date));
	$_pretty_date = date("l, jS F Y", strtotime($_std_date));

	$_wind_speed = $_result["average"];
	$_gust_speed = $_result["gust"];
	$_pressure = $_result["absolute"];
	$_rainfall = $_result["rainfall"];
	$_humidity = $_result["humidity"];
	$_direction = $_result["direction"];

        $_a = 17.27;
        $_b = 237.7;
        $_c = (($_a * $_temperature) / ($_b + $_temperature)) + log(($_humidity/100));
        $_dew_point = round(($_b * $_c) / ($_a - $_c),1);

        $_cloud_base = ($_temperature - $_dew_point) * 400;
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8"/>
	<title>Weather Station Data</title>
</head>
<body>
	<h1>Current Conditions:</h1>

	<p><strong>Current Temperature:</strong> <?php print($_temperature); ?>&ordm;c<br/>
	   <strong>Feels Like:</strong> <?php print($_feels_like); ?>&ordm;c<br/>
	   <br/>
	   <strong>Wind Speed:</strong> <?php print($_wind_speed); ?>mph<br/>
	   <strong>Gusts:</strong> <?php print($_gust_speed); ?>mph<br/>
	   <strong>Direction:</strong> <?php if($_direction>"") { print($_direction); } else { print("Calm"); } ?><br/>
	   <br/>
	   <strong>Humidity:</strong> <?php print($_humidity); ?>%<br/>
	   <br/>
	   <strong>Pressure:</strong> <?php print($_pressure); ?>hPa<br/>
	   <strong>Dew Point:</strong> <?php print($_dew_point); ?>&ordm;c<br/>
	   <strong>Cloud Base:</strong> <?php print($_cloud_base); ?> feet<br/>
	   <br/>
	   <strong>Rainfall:</strong> <?php print($_rainfall); ?>mm<br/>
	   <br/>
	   Last updated at <?php print($_badge_time . " on " . $_pretty_date); ?>.</p>

	<table>
		<caption>Historical Record of Weather Conditions<br/>(Average over the Hour)</caption>
		<thead>
			<tr>
				<th scope="col">Time</th>
				<th scope="col">&ordm;C</th>
				<th scope="col">Humidity</th>
				<th scope="col" colspan="3">Wind</th>
				<th scope="col">Rain</th>
				<th scope="col">Pressure</th>
			</tr>
                        <tr>
                                <th scope="col" colspan="3"></th>
                                <th scope="col">Direction</th>
                                <th scope="col">Speed</th>
                                <th scope="col">Gusts</th>
                                <th scope="col" colspan="2"></th>
                        </tr>
		</thead>
		<tbody>
<?php
        $_database = new HourlyDB();
        $_query = $_database->query("SELECT * FROM hourly ORDER BY stamp DESC LIMIT 6");

	while($_result = $_query->fetchArray(SQLITE3_ASSOC))
	{
		$_this_time = substr($_result["stamp"],8,2) . ":" . substr($_result["stamp"],10,2);
		$_this_temp = $_result["temperature"];
		$_this_humidity = $_result["humidity"];
		$_this_direction = $_result["direction"];
		if($_this_direction == "") { $_this_direction = "Calm"; }
		$_this_speed = $_result["average"];
		$_this_gusts = $_result["gust"];
		$_this_rain = $_result["rainfall"];
		$_this_pressure = $_result["relative"];

?>
			<tr>
				<td><?php print($_this_time); ?></td>
				<td><?php print($_this_temp); ?>&ordm;c</td>
				<td><?php print($_this_humidity); ?>%</td>
				<td><?php print($_this_direction); ?></td>
				<td><?php print($_this_speed); ?>mph</td>
				<td><?php print($_this_gusts); ?>mph</td>
				<td><?php print($_this_rain); ?>mm</td>
				<td><?php print($_this_pressure); ?>hPa</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</body>
</html>
