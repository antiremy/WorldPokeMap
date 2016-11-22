<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
// $hosts = [
// 	'10.0.0.3',
// 	'10.0.0.4',
// 	'10.0.0.5'
// ];

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];
$pid = $_POST['pid'];
$json = '';
$json = '{
     "query":{
        "bool":{
          "must": {
            "term" : {
              "pokemon_id" : "' . $pid . '"
            }
          },
           "filter":{
              "geo_bounding_box":{
                 "location":{
                    "top_left":{
                       "lat": ' . $northWestLat . ',
                       "lon": ' . $northWestLng . '
                    },
                    "bottom_right":{
                       "lat":' . $southEastLat . ',
                       "lon":' . $southEastLng . '
                    }
                 }
              }
           }
        }
     }
   }';

 $client = ClientBuilder::create()->setHosts(['10.132.63.179'])->build();
 $params = [
   'index' => 'pokemon',
   'type' => 'point',
   'size' => 1000,
   'body' => $json
 ];



 try {
   $response = $client->search($params);
   echo json_encode($response);
 } catch (Exception $e) {
   echo('Error: ' . $e->getMessage() . '\n');
 }




//  $params = [
//      "search_type" => "scan",    // use search_type=scan
//      "scroll" => "2s",          // how long between scroll requests. should be small!
//      "size" => 500,               // how many results *per shard* you want back
//      "index" => "pokemon",
//      "body" => $json
//  ];
//
// try {
//   $docs = $client->search($params); // Execute the search
//   $scroll_id = $docs['_scroll_id'];   // The response will contain no results, just a _scroll_id
//
//   // Now we loop until the scroll "cursors" are exhausted
//   while (\true) {
//
//       // Execute a Scroll request
//       $response = $client->scroll([
//               "scroll_id" => $scroll_id,  //...using our previously obtained _scroll_id
//               "scroll" => "2s"           // and the same timeout window
//           ]
//       );
//
//       // Check to see if we got any search hits from the scroll
//       if (count($response['hits']['hits']) > 0) {
//           echo json_encode($response);
//           // If yes, Do Work Here
//
//           // Get new scroll_id
//           // Must always refresh your _scroll_id!  It can change sometimes
//           $scroll_id = $response['_scroll_id'];
//       } else {
//           // No results, scroll cursor is empty.  You've exported all the data
//           break;
//       }
//   }
// } catch (Exception $e) {
//   echo('Error: '. $e->getMessage());
// }

?>
