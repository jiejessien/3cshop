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
	//$cart->get_contents();
	$updateid =$_GET["updateid"];
	$updateqty = $_GET["updateqty"];
	echo "<subtotal>$ ".number_format($cart->itemprices[$updateid]*$updateqty)."</subtotal>";
	//grandtotal
	//運費
}
echo "</item>";
?>
