<?php
$json = file_get_contents('php://input');
$gjson = json_decode($json);
$string = file_get_contents("pokemon.json");
$rarities = json_decode($string);
define('TIMEZONE','UTC');
date_default_timezone_set(TIMEZONE);
use Elasticsearch\ClientBuilder;
require 'vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(['10.132.82.141','10.132.77.55'])->build();

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
  $tth_found = $gjson->tth_found;
  $time = date("Y/m/d H:i:s T", $gjson->message->disappear_time);
  $rarity = $rarities->$pid->rarity;
  if ($rarity == "Common") {
    $rarity = "1";
  }
  else if ($rarity == "Uncommon") {
    $rarity = "2";
  }
  else if ($rarity == "Rare") {
    $rarity = "3";
  }
  else if ($rarity == "Very Rare") {
    $rarity = "4";
  }
  else if ($rarity == "Ultra Rare") {
    $rarity = "5";
  }
  $params = [
    'index' => 'pokemon',
    'type' => 'point',
    'id' => $eid,
    'body' => [
      'location' => [$lon,$lat],
      'disappearTime' => $time,
      'rarity' => $rarity,
      'pokemon_id' => $pid,
      'iv_attack' => $attack,
      'iv_defense' => $defense,
      'iv_stamina' => $stamina,
      'move_1' => $move1,
      'move_2' => $move2,
      'timestamp' => date("Y/m/d H:i:s T"),
      'tth_found' => $tth_found
    ]
  ];
  $response = $client->index($params);
}
else if ($gjson->type == "gym") {
  $teamID = $gjson->message->team_id;
  $lat = $gjson->message->latitude;
  $lon = $gjson->message->longitude;
  $guardPokeID = $gjson->message->guard_pokemon_id;
  $gymID = $gjson->message->gym_id;
  if ( base64_encode(base64_decode($gymID, true)) === $gymID){
    $gymID = base64_decode($gymID);
    echo '$data is valid';
  }
  $gymPoints = $gjson->message->gym_points;
  $params = [
    'index' => 'gyms',
    'type' => 'gym',
    'id' => $gymID,
    'body' => [
      'location' => [$lon,$lat],
      'team_id' => $teamID,
      'guard_pokemon_id' => $guardPokeID,
      'timestamp' => date("Y/m/d H:i:s T"),
      'gym_points' => $gymPoints
    ]
  ];
  $response = $client->index($params);
}
else if ($gjson->type == "gym_details") {
  $teamID = $gjson->message->team;
  $lat = $gjson->message->latitude;
  $lon = $gjson->message->longitude;
  $gymID = $gjson->message->id;
  $pokemon = $gjson->message->pokemon;
  $url = $gjson->message->url;
  $name = $gjson->message->name;
  $description = $gjson->message->description;
  $params = [
    'index' => 'gyms',
    'type' => 'gym_details',
    'id' => $gymID,
    'body' => [
      'location' => [$lon,$lat],
      'team_id' => $teamID,
      'timestamp' => date("Y/m/d H:i:s T"),
      'url' => $url,
      'name' => $name,
      'description' => $description,
      'pokemon' => []
    ]
  ];
  foreach ($pokemon as $poke) {
    array_push($params['body']['pokemon'],$poke);
  }
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
