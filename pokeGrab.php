<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];
$json = [
  'query' => [
    'bool' => [
      'must_not' => [
        ['terms' => ['_id' => []]],
        ['terms' => ['rarity' => []]]
      ],
      'must' => [
        'range' => [
          'disappearTime' => [
            'gte' => 'now',
            'lte' => 'now+1d'
          ]
        ]
      ],
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
if (isset($_POST['common'])) {
  array_push($json['query']['bool']['must_not'][1]['terms']['rarity'],'1');
}
if (isset($_POST['uncommon'])) {
  array_push($json['query']['bool']['must_not'][1]['terms']['rarity'],'2');
}
if (isset($_POST['rare'])) {
  array_push($json['query']['bool']['must_not'][1]['terms']['rarity'],'3');
}
if (isset($_POST['veryrare'])) {
  array_push($json['query']['bool']['must_not'][1]['terms']['rarity'],'4');
}
if (isset($_POST['ultrarare'])) {
  array_push($json['query']['bool']['must_not'][1]['terms']['rarity'],'5');
}
if (isset($_POST['encID'])) {
  $json['query']['bool']['must_not'][0]['terms']['_id'] = $_POST['encID'];
}
$client = ClientBuilder::create()->setHosts(['10.132.84.37'])->build();
 $params = [
   'index' => 'pokemon',
   'type' => 'point',
   'size' => 1000,
   'body' => $json
 ];

#echo json_encode($json);

 try {
   $response = $client->search($params);
#   echo json_encode($response);
   echo json_encode(array($response, "time"=>time()));
 } catch (Exception $e) {
   echo('Error: ' . $e->getMessage() . '\n');
 }

?>
