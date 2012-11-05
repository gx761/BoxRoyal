<?php
/*
 * @
 */

$serachTerm = "lebron james"; // original search term.
$keywords = explode ( ' ', $searchTerm );
$query;
for($i = 0; $i < count ( $keywords ); $i ++) {
	if ($keywords [i] != '') {
		$query .= $keyword [i] . '%20'; // build a query
	}
}
$finalQuery = rtrim ( $query, '%20' ); // remove the last %20 from the above query

$request = 'http://search.twitter.com/search.json?q=' . $finalQuery . '&rpp=100&include_entities=true'; // the
                                                                                                    // final
                                                                                                    // request

$st = curl_init ( $request ); // initialize a crul request

curl_setopt ( $st, CURLOPT_RETURNTRANSFER, 1 );

$response = curl_exec ( $st );
$results = json_decode ( $response, 1 );

$list = array ();

/*
 * @ store the decoded data into a new array
 */
foreach ( $results ['results'] as $result ) {
	
	$list [] = array (
			'user' => $result ['from_user'],
			'text' => $result ['text'],
			'date' => $result ['created_at'] 
	);
}

$m = new Mongo ( 'localhost' ); // instantiate a new mongo object
$db = $m->selectDB ( 'tweets' ); // select a database, the new database is created
                              // on the fly
$collection = new MongoCollection ( $db, 'tweets_collection' ); // build a new
                                                            // connection
$collection->insert ( $list ); // insert the data into the connection.

/*
 * @ retrive data from the database.
 */
$m = new Mongo ( localhost );
$db = $m->selectDB ( 'tweets' );
$criteria = $cursor = $db->selectCollection ( 'tweets_collection' )->find ();
$cursor->sort ( array (
		'date' => 1 
) );
$cursor->limit ( 20 );
echo '<h2>The 20 tweets for search term:' . $searchTerm . 'is as following</h2><br>';
echo '<hr><table>';
echo '<tr><th>' . $obj ['user'] . '</th><th>' . $obj ['text'] . '</th><th>' . $obj ['date'] . '</th></tr>';
foreach ( $cursor as $obj ) {
	echo '<td>' . $obj ['user'] . '</td><td>' . $obj ['text'] . '</td><td>' . $obj ['date'] . '</td>';
}

echo '</table>';






