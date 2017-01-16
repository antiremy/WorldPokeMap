<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
// $hosts = [
//         '10.0.0.3',
//         '10.0.0.4',
//         '10.0.0.5'
// ];


$client = ClientBuilder::create()->setHosts(['10.132.82.141','10.132.77.55'])->build();

$northWestLat = $_POST['northWestLat'];
$northWestLng = $_POST['northWestLng'];
$southEastLat = $_POST['southEastLat'];
$southEastLng = $_POST['southEastLng'];
$zoom = $_POST['zoom'];
$pid = $_POST['pid'];
$rarities = ['body'=>[]];
if (!isset($_POST['common'])) {
  $rarities['body'][] = ['index' => 'pokemon', 'type' => 'point'];
  $rarities['body'][] = [
    'size' => 1,
    'query' => [
      'bool' => [
        'must' => [
          'range' => [
            'disappearTime' =>[
              'gte' => 'now'
            ]
          ]
        ],
        'must' => [
          'match' =>[
            'rarity' => 'Common'
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
}
if (!isset($_POST['uncommon'])) {
  $rarities['body'][] = ['index' => 'pokemon', 'type' => 'point'];
  $rarities['body'][] = [
    'size' => 1,
    'query' => [
      'bool' => [
        'must' => [
          'range' => [
            'disappearTime' =>[
              'gte' => 'now'
            ]
          ]
        ],
        'must' => [
          'match' =>[
            'rarity' => 'Uncommon'
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
}
// if (!isset($_POST['rare'])) {
//   $rarities['body'][] = ['index' => 'pokemon', 'type' => 'point'];
//   $rarities['body'][] = [
//     'size' => 1,
//     'query' => [
//       'bool' => [
//         'should' => [
//           'range' => [
//             'disappearTime' =>[
//               'gte' => 'now'
//             ]
//           ]
//         ],
//         'must' => [
//           'match' =>[
//             'rarity' => 'Rare'
//           ]
//         ],
//         'filter' => [
//           'geo_bounding_box' => [
//             'location' => [
//               'top_left' => [
//                 'lat' => $northWestLat,
//                 'lon' => $northWestLng
//               ],
//               'bottom_right' => [
//                 'lat' => $southEastLat,
//                 'lon' => $southEastLng
//               ]
//             ]
//           ]
//         ]
//       ]
//     ],
//     'aggs' => [
//       'zoom' => [
//         'geohash_grid' => [
//           'field' => 'location',
//           'precision' => $zoom
//         ]
//       ]
//     ]
//     ];
// }
if (!isset($_POST['veryrare'])) {
  $rarities['body'][] = ['index' => 'pokemon', 'type' => 'point'];
  $rarities['body'][] = [
    'size' => 1,
    'query' => [
      'bool' => [
        'must' => [
          'range' => [
            'disappearTime' =>[
              'gte' => 'now'
            ]
          ]
        ],
        'must' => [
          'match' =>[
            'rarity' => 'Very'
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
}
if (!isset($_POST['ultrarare'])) {
  $rarities['body'][] = ['index' => 'pokemon', 'type' => 'point'];
  $rarities['body'][] = [
    'size' => 1,
    'query' => [
      'bool' => [
        'must' => [
          'range' => [
            'disappearTime' =>[
              'gte' => 'now'
            ]
          ]
        ],
        'must' => [
          'match' =>[
            'rarity' => 'Ultra'
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
}

// DEPRECATED FOR NATURAL PHP ARRAY
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

$params = [
  'index' => 'pokemon',
  'type' => 'point',
  'size' => 0,
  'body' => [
    'query' => [
      'bool' => [
        'must' => [
          'term' => [
            'pokemon_id' => $pid
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
  ]
];



try {
  $responses = $client->search($params);
  echo json_encode($responses);
  // forEach ($responses as $response) {
  //   echo json_encode($response);
  // }
} catch (Exception $e) {
  echo('Error: ' . $e->getMessage() . '\n');
}


?>
