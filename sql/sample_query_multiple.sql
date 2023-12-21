SELECT DATE_FORMAT(timestamp, "%M %d, %Y") as day, thermometer_id as location, AVG(temperature) as temperature
FROM AC_Thermometer_Log
WHERE timestamp >= '2023-01-05' and timestamp < '2023-01-07'
GROUP BY location, day
ORDER BY location, day ASC;
