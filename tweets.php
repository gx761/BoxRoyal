<?php
/*
 * @
 */

$searchTerm = "lebron james"; // original search term.
$keywords = explode (' ',$searchTerm);
$query='';
for($i = 0; $i < count ( $keywords ); $i ++) {
	if ($keywords [$i] != '') {
		$query.= $keywords[$i] . '%20'; // build a query
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
foreach ( $results ['results'] as $result ) 
{
	
	$list[] = array (
			'user' => $result ['from_user'],
			'text' => $result ['text'],
			'date' => date("l M j \- g:ia",strtotime($result ['created_at']))
					);
	
}


$m = new Mongo (); // instantiate a new mongo object
$db = $m->selectDB ('tweets');
$db->createCollection('tweets_collection'); // select a database, the new database is created
                              // on the fly

$cursor = $db->tweets_collection->find();

$cursor->sort ( array (
		'date' => 1 
) );

$cursor->limit(20)->batchSize(20);
$result = $cursor->getNext();

echo '<h2>The 20 tweets for search term:' . $searchTerm . 'is as following</h2><br>';
echo '<hr><table>';
echo '<tr><th>User</th><th>Text</th><th>Date</th></tr>';

for($i=0;$i<20;$i++)
{
	echo '<tr><td>' . $result[$i]['user'] . '</td><td>' . $result[$i]['text'] . '</td><td>' . $result[$i]['date'] . '</td></tr>';
}

echo '</table>';

