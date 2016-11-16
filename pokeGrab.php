<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
// $hosts = [
// 	'10.0.0.3',
// 	'10.0.0.4',
// 	'10.0.0.5'
// ];


$client = ClientBuilder::create()->setHosts(['10.132.63.179'])->build();

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];


$json = '{
   "query":{
      "bool":{
        "must": {
          "range" : {
            "disappearTime" : {"gte": "now","lte": "now+1d"}
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

?>
