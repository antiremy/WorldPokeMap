<?php
// functions.php

function check_txnid($tnxid){
	global $link;
	return true;
	$valid_txnid = true;
	//get result set
	$sql = mysqli_query("SELECT * FROM `payments` WHERE txnid = '$tnxid'", $link);
	if ($row = mysqli_fetch_array($sql)) {
		$valid_txnid = false;
	}
	return $valid_txnid;
}

function check_price($price, $id){
	$valid_price = false;
	//you could use the below to check whether the correct price has been paid for the product

	/*
	$sql = mysqli_query("SELECT amount FROM `products` WHERE id = '$id'");
	if (mysqli_num_rows($sql) != 0) {
		while ($row = mysqli_fetch_array($sql)) {
			$num = (float)$row['amount'];
			if($num == $price){
				$valid_price = true;
			}
		}
	}
	return $valid_price;
	*/
	return true;
}

function updatePayments($data){
	global $link;

	if (is_array($data)) {
		$sql = mysqli_query($link,"INSERT INTO payments (firstName, lastInitial, email, payment_amount, payment_status, userID, locationID, createdtime) VALUES (
				'".$data['firstName']."' ,
				'".$data['lastInitial']."' ,
				'".$data['payer_email']."' ,
				'".$data['payment_amount']."' ,
				'".$data['payment_status']."' ,
				'".$data['custom']."' ,
				'".$data['item_number']."',
				'".date("Y-m-d H:i:s")."'
				)");
		return mysqli_insert_id($link);
	}
}
