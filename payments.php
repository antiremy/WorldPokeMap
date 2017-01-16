<?php

// Database variables
$host = "localhost"; //database location
$user = "paypal"; //database username
$pass = "WxRLbfomXBP9f2"; //database password
$db_name = "users"; //database name

// PayPal settings
$paypal_email = 'antiremy@me.com';
$return_url = 'https://soflopokego.xyz/site/payment-successful.html';
$cancel_url = 'https://soflopokego.xyz/site/payment-cancelled.html';
$notify_url = 'https://soflopokego.xyz/site/payments.php';


// Include Functions
include("functions.php");

// Check if paypal request or response
if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])){
	$querystring = '';

	// Firstly Append paypal account to querystring
	$querystring .= "?business=".urlencode($paypal_email)."&";

	// Append amount& currency (£) to quersytring so it cannot be edited in html

	//The item name and amount can be brought in dynamically by querying the $_POST['item_number'] variable.
	// $querystring .= "item_name=".urlencode($item_name)."&";
	// $querystring .= "amount=".urlencode($item_amount)."&";

	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}

	// Append paypal return addresses
	$querystring .= "return=".urlencode(stripslashes($return_url))."&";
	$querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
	$querystring .= "notify_url=".urlencode($notify_url);

	// Append querystring with custom field
	//$querystring .= "&custom=".USERID;

	// Redirect to paypal IPN
	header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring);
	//header('location:https://www.sandbox.paypal.com/cgi-bin/webscr'.$querystring);

	exit();
} else {
	//Database Connection
	$link = mysqli_connect($host, $user, $pass, $db_name);

	// Response from Paypal

	// read the post from PayPal system and add 'cmd'
	$req = 'cmd=_notify-validate';
	foreach ($_POST as $key => $value) {
		$value = urlencode(stripslashes($value));
		$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
		$req .= "&$key=$value";
	}

	// assign posted variables to local variables
	$data['item_name']			= $_POST['item_name1'];
	$data['item_number'] 		= $_POST['item_number1'];
	$data['payment_status'] 	= $_POST['payment_status'];
	$data['payment_amount'] 	= $_POST['mc_gross'];
	$data['payment_currency']	= $_POST['mc_currency'];
	$data['txn_id']				= $_POST['txn_id'];
	$data['receiver_email'] 	= $_POST['receiver_email'];
	$data['payer_email'] 		= $_POST['payer_email'];
	$data['custom'] 			= $_POST['custom'];
	$data['firstName'] 		= $_POST['first_name'];
	$data['lastInitial']	= substr($_POST['last_name'], 1);

	// post back to PayPal system to validate

	$header = "POST /cgi-bin/webscr HTTP/1.1\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Host: www.paypal.com\r\n";
	$header .= "Connection: close\r\n\r\n";


	// $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
	// $header .= "Host: www.sandbox.paypal.com\r\n";
	// $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	// $header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

	if (!$fp) {
		// HTTP ERROR

	} else {
		fputs($fp, $header . $req);
		while (!feof($fp)) {
			$res = fgets ($fp, 1024);
			if (strcmp (trim($res), "VERIFIED") == 0) {

				// Used for debugging
				 error_log('PAYPAL POST - VERIFIED RESPONSE');

				// Validate payment (Check unique txnid & correct price)
				$valid_txnid = check_txnid($data['txn_id']);
				$valid_price = check_price($data['payment_amount'], $data['item_number']);
				// PAYMENT VALIDATED & VERIFIED!
				if ($valid_txnid && $valid_price) {

					$orderid = updatePayments($data);

					if ($orderid) {
						error_log('PAYPAL POST - INSERT INTO DB WENT RIGHT :)');
						// Payment has been made & successfully inserted into the Database
					} else {
						// Error inserting into DB
						// E-mail admin or alert user
						 error_log('PAYPAL POST - INSERT INTO DB WENT WRONG');
						 error_log(mysqli_error($link));
					}
				} else {
					error_log('PAYPAL POST - DATA WAS CHANGED');
					// Payment made but data has been changed
					// E-mail admin or alert user
				}

			} else if (strcmp (($res), "INVALID") == 0) {
				error_log('PAYPAL POST - PAYMENT INVALID');
				error_log($res);

				// PAYMENT INVALID & INVESTIGATE MANUALY!
				// E-mail admin or alert user

				// Used for debugging
				//@mail("user@domain.com", "PAYPAL DEBUGGING", "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
			} else {
				error_log($res);
			}
		}
	fclose ($fp);
	}
}
?>
