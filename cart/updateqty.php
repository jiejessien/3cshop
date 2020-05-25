<?php
header("Content-Type: text/xml");

include("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();

echo "<?xml version=\"1.0\" ?>";
echo "<item>";
if ( isset($_GET["updateqty"]) && $_GET["updateqty"] != '' ){
	$cart->edit_item($_GET["updateid"],$_GET["updateqty"]);
	$updateid =$_GET["updateid"];
	$updateqty = $_GET["updateqty"];
	echo "<subtotal>$ ".number_format($cart->itemprices[$updateid]*$updateqty)."</subtotal>";
	echo "<grandtotal>$ ".number_format($cart->grandtotal)."</grandtotal>";
	echo "<deliverfee>$ ".number_format($cart->deliverfee)."</deliverfee>";
}
echo "</item>";
?>
