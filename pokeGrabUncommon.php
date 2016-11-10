<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
$client = ClientBuilder::create()->build();

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];


$json = '{
   "query":{
      "bool":{
        "must": {
          "match": {
            "rarity": "Uncommon"
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
   'size' => 10000,
   'body' => $json
 ];



 try {
   $response = $client->search($params);
   echo json_encode($response);
 } catch (Exception $e) {
   echo('Error: ' . $e->getMessage() . '\n');
 }

?>
