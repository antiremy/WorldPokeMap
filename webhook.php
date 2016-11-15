<?php
$json = file_get_contents('php://input');
$gjson = json_decode($json);
$string = file_get_contents("pokemon.json");
$rarities = json_decode($string);
define('TIMEZONE','UTC');
date_default_timezone_set(TIMEZONE);
use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';
$client = ClientBuilder::create()->build();

if($gjson->type == "pokemon") {
  $eid = $gjson->message->encounter_id;
  $pid = $gjson->message->pokemon_id;
  $pid = (int)$pid;
  $lat = $gjson->message->latitude;
  $lon = $gjson->message->longitude;
  $attack = $gjson->message->individual_attack;
  $defense = $gjson->message->individual_defense;
  $stamina = $gjson->message->individual_stamina;
  $move1 = $gjson->message->move_1;
  $move2 = $gjson->message->move_2;
  $time = date("Y/m/d H:i:s T", $gjson->message->disappear_time);
  $rarity = $rarities->$pid->rarity;
  $params = [
    'index' => 'pokemon',
    'type' => 'point',
    'id' => $eid,
    'body' => [
      'location' => [$lat,$lon],
      'disappearTime' => $time,
      'rarity' => $rarity,
      'pokemon_id' => $pid,
      'iv_attack' => $attack,
      'iv_defense' => $defense,
      'iv_stamina' => $stamina,
      'move_1' => $move1,
      'move_2' => $move2
    ]
  ];
  $response = $client->index($params);
}
    // ob_start();
    // var_dump($rarities);
    // $output = ob_get_clean();
    // error_log($output);
    //CONNECTION DETAILS

    // $query->bindParam(':enc', $gjson->message->encounter_id);
    // $query->bindParam(':spawn', $gjson->message->spawnpoint_id);
    // $query->bindParam(':id', $gjson->message->pokemon_id);
    // $query->bindParam(':lat', $gjson->message->latitude);
    // $query->bindParam(':lon', $gjson->message->longitude);
    // $time = date("Y-m-d H:i:s", $gjson->message->disappear_time);
    // $query->execute();


//$dt = new DateTime();
//$dt->setTimestamp($gjson->message->disappear_time);
//$query->bindParam(':time', $dt);

// Else just do nothing
?>
