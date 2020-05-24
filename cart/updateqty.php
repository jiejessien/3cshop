<?php
require_once("../connMysql.php");
include("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
if ( isset($_POST["updateqty"]) && $_POST["updateqty"] != '' ){
	$cart->edit_item($_POST["updateid"],$_POST["updateqty"]);
	//echo "<p id='subtotal'>$ </p>";
}
?>
