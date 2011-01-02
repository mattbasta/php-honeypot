
/*Hits*/

	SELECT COUNT( * ) AS c, FLOOR( TIMESTAMP /3600 ) AS t, FROM_UNIXTIME( TIMESTAMP ) AS ts
	FROM  `hits` 
	GROUP BY t
	LIMIT 0 , 30
	
/*Spam Sources*/

	SELECT spams.ip, hits.ip, url_served.path, url_served.ip 
	FROM  `hits` ,  `spams` ,  `url_served` 
	WHERE hits.ip = spams.ip AND url_served.path = hits.path