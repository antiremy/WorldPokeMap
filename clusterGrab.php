<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
// $hosts = [
//         '10.0.0.3',
//         '10.0.0.4',
//         '10.0.0.5'
// ];

$client = ClientBuilder::create()->setHosts(['10.132.84.37'])->build();

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];
$json = [
  'query' => [
    'bool' => [
      'must_not' => [
        'terms' => ['rarity' => []],
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
  ],
  'aggs' => [
    'zoom' => [
      'geohash_grid' => [
        'field' => 'location',
        'precision' => $zoom
      ]
    ]
  ]
];
// $json = '{
//    "query":{
//       "bool":{
//         "must": {
//           "range" : {
//             "disappearTime" : {"gte": "now","lte": "now+1d"}
//           }
//         },
//          "filter":{
//             "geo_bounding_box":{
//                "location":{
//                   "top_left":{
//                      "lat": ' . $northWestLat . ',
//                      "lon": ' . $northWestLng . '
//                   },
//                   "bottom_right":{
//                      "lat":' . $southEastLat . ',
//                      "lon":' . $southEastLng . '
//                   }
//                }
//             }
//          }
//       }
//    },
//    "aggs":{
//      "zoom":{
//         "geohash_grid":{
//            "field":"location",
//            "precision":' . $zoom . '
//         }
//      }
//    }
// }';

if (isset($_POST['common'])) {
  array_push($json['query']['bool']['must_not']['terms']['rarity'],'1');
}
if (isset($_POST['uncommon'])) {
  array_push($json['query']['bool']['must_not']['terms']['rarity'],'2');
}
if (isset($_POST['rare'])) {
  array_push($json['query']['bool']['must_not']['terms']['rarity'],'3');
}
if (isset($_POST['veryrare'])) {
  array_push($json['query']['bool']['must_not']['terms']['rarity'],'4');
}
if (isset($_POST['ultrarare'])) {
  array_push($json['query']['bool']['must_not']['terms']['rarity'],'5');
}

$params = [
  'index' => 'pokemon',
  'type' => 'point',
  'size' => 1,
  'body' => $json
];



try {
  $response = $client->search($params);
  echo json_encode($response);
} catch (Exception $e) {
  echo('Error: ' . $e->getMessage() . '\n');
}


?>
