<?php
require_once("../connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();

//更新購物車數量
if ( isset($_POST["updateqty"]) && $_POST["updateqty"] != '' ){
	$cart->edit_item($_POST["updateid"],$_POST["updateqty"]);
	
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
	
	//使用Ajax方法更新購物車
	var itemNum=$("input[id^='qty[]']").length;
	for(i=0;i<itemNum;i++){
		cartaction(i);
	}
	function cartaction(j){
		$("input[id^='qty[]']").eq(j).on("change",function(){
			var qtyVal= $(this).val(); 
			var idVal=$("input[id^='updateid[]']").eq(j).val() ;
			$.ajax({
				type:'GET',
				url:'updateqty.php',
				data: { updateqty : qtyVal ,
					updateid : idVal },
				success:function(data){					
					var subtotal = $(data).find("subtotal").text();	
					var grandtotal = $(data).find("grandtotal").text();	
					var deliverfee = $(data).find("deliverfee").text();						
					$("p[id^='subtotal[]']").eq(j).text(subtotal);
					$("p#deliverfee").text(deliverfee);
					$("p#grandtotal").text(grandtotal);	
				}				
			});
			return false;			
		});		
	}	
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
              <tr class="table-product item_row">
                <td  ><p><a href="?cartaction=remove&delid=<?php echo $item['id'];?>">移除</a></p></td>
                <td  ><p><?php echo $item['info'];?></p></td>
                <td  ><p>
                  <input name="updateid[]" type="hidden" id="updateid[]" value="<?php echo $item['id'];?>">
                  <input name="qty[]" type="number" id="qty[]" style="width:100%" min="1" max="20" value="<?php echo $item['qty'];?>" >
                  </p></td>
                <td  ><p id="itemprice[]">$ <?php echo number_format($item['price']);?></p></td>
                <td  ><p id="subtotal[]">$ <?php echo number_format($item['subtotal']);?></p></td>
              </tr>
          <?php }?>
              <tr>
                <td  ><p>運費</p></td>
                <td colspan="3" ><p>&nbsp;</p></td>

                <td ><p id="deliverfee">$ <?php echo number_format($cart->deliverfee);?></p></td>
              </tr>
              <tr>
                <td ><p>總計</p></td>
                <td colspan="3" ><p>&nbsp;</p></td>

                <td  ><p class="redword" id="grandtotal">$ <?php echo number_format($cart->grandtotal);?></p></td>
              </tr>          
            </table>
            <hr width="100%" size="1" />
            <div class="btnrow">
              <input name="cartaction" type="hidden" id="cartaction" value="update">
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