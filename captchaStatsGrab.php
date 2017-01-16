<?php
if (isset($_POST)) {
  $apiKey = "***REMOVED***";
  $action = $_POST['action'];

  if ($action == "getbalance") {
    $url = 'https://2captcha.com/res.php?' . 'action='. $action . '&' . 'key=' . $apiKey;
  }
  else if ($action == "getstats") {
    $url = 'https://2captcha.com/res.php?' . 'action='. $action . '&key=' . $apiKey . '&date=' . $_POST['date'];
  }


  echo json_encode(file_get_contents($url));
 }
?>
