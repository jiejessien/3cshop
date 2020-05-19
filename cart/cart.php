<?php
require_once("../connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
// 更新購物車內容
if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="update")){
	if(isset($_POST["updateid"])){
		$i=count($_POST["updateid"]);
		for($j=0;$j<$i;$j++){
			$cart->edit_item($_POST['updateid'][$j],$_POST['qty'][$j]);
		}
	}
	header("Location: cart.php");
}
// 移除購物車內容
if(isset($_GET["cartaction"]) && ($_GET["cartaction"]=="remove")){
	$rid = intval($_GET['delid']);
	$cart->del_item($rid);
	header("Location: cart.php");	
}
// 清空購物車內容
if(isset($_GET["cartaction"]) && ($_GET["cartaction"]=="empty")){
	$cart->empty_cart();
	header("Location: cart.php");
}
//購物車結束
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid)as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網</title>
<link href="../css/cart_style.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-3.5.0.min.js"></script>
<script>
$(document).ready(function(){
  $("body").find("*").css("font-family", "微軟正黑體");
 
 });
</script>
</head>

<body>
<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>
<div class="box-large">
<p class="title">購物車內容</p>
<div class="content" > 

          <div class="normalDiv">
		  <?php if($cart->itemcount > 0) {?>
          <form action="" method="post" name="cartform" id="cartform">
          <table class="table-list">
              <tr>
                <th><p>刪除</p></th>
                <th ><p>產品名稱</p></th>
                <th ><p>數量</p></th>
                <th><p>單價</p></th>
                <th ><p>小計</p></th>
              </tr>
          <?php	foreach($cart->get_contents() as $item) { ?>              
              <tr class="table-product">
                <td  ><p><a href="?cartaction=remove&delid=<?php echo $item['id'];?>">移除</a></p></td>
                <td  ><p><?php echo $item['info'];?></p></td>
                <td  ><p>
                  <input name="updateid[]" type="hidden" id="updateid[]" value="<?php echo $item['id'];?>">
                  <input name="qty[]" type="text" id="qty[]" value="<?php echo $item['qty'];?>" size="1">
                  </p></td>
                <td  ><p>$ <?php echo number_format($item['price']);?></p></td>
                <td  ><p>$ <?php echo number_format($item['subtotal']);?></p></td>
              </tr>
          <?php }?>
              <tr>
                <td  ><p>運費</p></td>
                <td colspan="3" ><p>&nbsp;</p></td>

                <td ><p>$ <?php echo number_format($cart->deliverfee);?></p></td>
              </tr>
              <tr>
                <td ><p>總計</p></td>
                <td colspan="3" ><p>&nbsp;</p></td>

                <td  ><p class="redword">$ <?php echo number_format($cart->grandtotal);?></p></td>
              </tr>          
            </table>
            <hr width="100%" size="1" />
            <div class="btnrow">
              <input name="cartaction" type="hidden" id="cartaction" value="update">
              <input type="submit" name="updatebtn" id="button3" value="更新購物車">&nbsp;
              <input type="button" name="emptybtn" id="button5" value="清空購物車" onClick="window.location.href='?cartaction=empty'">&nbsp;
              <input type="button" name="button" id="button6" value="前往結帳" onClick="window.location.href='checkout.php';">&nbsp;
              <input type="button" name="backbtn" id="button4" value="繼續購物" onClick="window.history.back();">
              </p>
          </form>
          </div>          
            <?php }else{ ?>
            <div class="infoDiv">目前購物車是空的。</div>
          <?php } ?>
	          </div> </div></div> 


<div align="center"  class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>
</body>
</html>
<?php $db_link->close();?>