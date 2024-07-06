<?php

use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(['10.132.84.37'])->build();

$params = [
  'index' => 'pokemon',
  'type' => 'point',
  'size' => 0,
  'body' => [
    'query' => [
      'bool' => [
        'must' => [
          'range' => [
            'disappearTime' => [
              'gte' => 'now',
              'lte' => 'now+1d'
            ]
          ]
        ]
  ]]]];

$pokeStats = $client->search($params);

$params = [
  'index' => 'gyms',
  'type' => 'gym',
  'size' => 0,
  'body' => [
    'query' => [
      'term' => [
        'team_id' => 1
      ]
]]];

$mysticStats = $client->search($params);

$params = [
  'index' => 'gyms',
  'type' => 'gym',
  'size' => 0,
  'body' => [
    'query' => [
      'term' => [
        'team_id' => 2
      ]
]]];

$valorStats = $client->search($params);

$params = [
  'index' => 'gyms',
  'type' => 'gym',
  'size' => 0,
  'body' => [
    'query' => [
      'term' => [
        'team_id' => 3
      ]
]]];

$instinctStats = $client->search($params);

$from_unix_time = time();
$day_before = strtotime("yesterday", $from_unix_time);
$date = date('Y-m-d', $day_before);

$url = 'https://2captcha.com/res.php?action=getstats&key=86f7e2f2cfb26bc385bf94efbc4490f7&date=' . $date;


$output = file_get_contents($url);
$xml = simplexml_load_string($output);
$captchaJson = json_encode($xml);

$response = [
  'pokemon' => $pokeStats,
  'mystic' => $mysticStats,
  'valor' => $valorStats,
  'instinct' => $instinctStats,
  'captchas' => $captchaJson
];

file_put_contents("siteStats.json", json_encode($response));
echo "Success!";

?>
