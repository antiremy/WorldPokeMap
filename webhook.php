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
      'timestamp' => date("Y/m/d H:i:s T")
    ]
  ];
  $response = $client->index($params);
}
else if ($gjson->type == "gym") {
  file_put_contents("gymOutput.txt", print_r($gjson, True));
  $teamID = $gjson->message->team_id;
  $lat = $gjson->message->latitude;
  $lon = $gjson->message->longitude;
  $guardPokeID = $gjson->message->guard_pokemon_id;
  $gymID = $gjson->message->gym_id;
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
      // 'pokemon1' => [
      //   'num_upgrades' => $pokemon1->num_upgrades,
      //   'move_1' => $pokemon1->move_1,
      //   'move_2' => $pokemon1->move_2,
      //   'additional_cp_multiplier' => $pokemon1->additional_cp_multiplier,
      //   'iv_defense' => $pokemon1->iv_defense,
      //   'weight' => $pokemon1->weight,
      //   'pokemon_id' => $pokemon1->pokemon_id,
      //   'stamina_max' => $pokemon1->stamina_max,
      //   'cp_multiplier' => $pokemon1->cp_multiplier,
      //   'height' => $pokemon1->height,
      //   'stamina' => $pokemon1->stamina,
      //   'iv_attack' => $pokemon1->iv_attack,
      //   'trainer_name' => $pokemon1->trainer_name,
      //   'trainer_level' => $pokemon1->trainer_level,
      //   'cp' => $pokemon1->cp,
      //   'iv_stamina' => $pokemon1->iv_stamina
      // ],
      // 'pokemon2' => [
      //   'num_upgrades' => $pokemon2->num_upgrades,
      //   'move_1' => $pokemon2->move_1,
      //   'move_2' => $pokemon2->move_2,
      //   'additional_cp_multiplier' => $pokemon2->additional_cp_multiplier,
      //   'iv_defense' => $pokemon2->iv_defense,
      //   'weight' => $pokemon2->weight,
      //   'pokemon_id' => $pokemon2->pokemon_id,
      //   'stamina_max' => $pokemon2->stamina_max,
      //   'cp_multiplier' => $pokemon2->cp_multiplier,
      //   'height' => $pokemon2->height,
      //   'stamina' => $pokemon2->stamina,
      //   'iv_attack' => $pokemon2->iv_attack,
      //   'trainer_name' => $pokemon2->trainer_name,
      //   'trainer_level' => $pokemon2->trainer_level,
      //   'cp' => $pokemon2->cp,
      //   'iv_stamina' => $pokemon2->iv_stamina
      // ],
      // 'pokemon3' => [
      //   'num_upgrades' => $pokemon3->num_upgrades,
      //   'move_1' => $pokemon3->move_1,
      //   'move_2' => $pokemon3->move_2,
      //   'additional_cp_multiplier' => $pokemon3->additional_cp_multiplier,
      //   'iv_defense' => $pokemon3->iv_defense,
      //   'weight' => $pokemon3->weight,
      //   'pokemon_id' => $pokemon3->pokemon_id,
      //   'stamina_max' => $pokemon3->stamina_max,
      //   'cp_multiplier' => $pokemon3->cp_multiplier,
      //   'height' => $pokemon3->height,
      //   'stamina' => $pokemon3->stamina,
      //   'iv_attack' => $pokemon3->iv_attack,
      //   'trainer_name' => $pokemon3->trainer_name,
      //   'trainer_level' => $pokemon3->trainer_level,
      //   'cp' => $pokemon3->cp,
      //   'iv_stamina' => $pokemon3->iv_stamina
      // ]
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
