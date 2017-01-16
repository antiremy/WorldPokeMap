<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];
$json = '';
$params = '';
$json = [
  'query' => [
    'bool' => [
      'filter' => [
        'geo_bounding_box' => [
          'location' => [
            'top_left' => [
              'lat' => $northWestLat,
              'lon' => $northWestLng
            ],
            'bottom_right' => [
              'lat' => $southEastLat,
              'lon' => $southEastLng
            ]
          ]
        ]
      ]
    ]
  ]
];
$client = ClientBuilder::create()->setHosts(['10.132.84.37'])->build();

#echo json_encode($json);

 try {
   $params = [
     'index' => 'gyms',
     'type' => 'gym',
     'size' => 500,
     'body' => $json
   ];
   $responseGym = $client->search($params);
   $params = [
     'index' => 'gyms',
     'type' => 'gym_details',
     'size' => 500,
     'body' => $json
   ];
   $responseDetails = $client->search($params);
   echo json_encode(array($responseGym, $responseDetails, "time"=>time()));
#   echo json_encode($response);

 } catch (Exception $e) {
   echo('Error: ' . $e->getMessage() . '\n');
 }

?>
