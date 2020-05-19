<?php
require_once("../connMysql.php");
//購物車開始
require_once("../cart/mycart.php");
session_start();
$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
// 新增購物車內容
if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="add")){
	$cart->add_item($_POST['id'],$_POST['qty'],$_POST['price'],$_POST['name']);
	header("Location: ../cart/cart.php");
}
//購物車結束
//繫結產品資料
$query_RecProduct = "SELECT * FROM product WHERE productid=?";
$stmt = $db_link->prepare($query_RecProduct);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct = $RecProduct->fetch_assoc();
//繫結留言資料
$query_RecComment="SELECT * FROM comment WHERE c_productid=? ORDER BY c_datetime DESC";
$stmt2=$db_link->prepare($query_RecComment);
$stmt2->bind_param("i",$_GET["id"]);
$stmt2->execute();
$RecComment=$stmt2->get_result();
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網</title>
<link href="../css/product_style.css" rel="stylesheet" type="text/css">
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

<div class="box-primary ">
<table >
	<tr>
		<td class="box-tiny">
             
			<div class="regbox box-center">
			 <h1> 產品搜尋 </h1>
              <form name="form1" method="get" action="product.php">
                <p>
                  <input name="keyword" type="text" id="keyword" placeholder="請輸入關鍵字" size="12" onClick="this.value='';">
                  
                </p>
              
              <h1> 價格區間 </h1>
              
                <p>
                  <input name="price1" type="text" id="price1" placeholder="最低" size="5">
                  -
                  <input name="price2" type="text" id="price2" placeholder="最高" size="8">
				  
				<br/><input type="submit" id="button" value="查詢">
                </p>
              </form>
            </div>
		</td>
		<td rowspan="2" class="box-large top" >
			
			
			<p class="title"> 產品詳細資料</p>
          <div class="product_primary">
          <div class="albumDiv">
            <div class="picDiv">
              <?php if($row_RecProduct["productimages"]==""){?>
              <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
              <?php }else{?>
              <img src="proimg/<?php echo $row_RecProduct["productimages"];?>" alt="<?php echo $row_RecProduct["productname"];?>" width="135" height="135" border="0" />
              <?php }?>
            </div>
            <div class="albuminfo"><span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["productprice"];?></span><span class="smalltext"> 元</span>            </div>
          </div>
		   <div class="dataDiv" >
          <div class="titleDiv">
            <?php echo $row_RecProduct["productname"];?></div>

            <p><?php echo nl2br($row_RecProduct["description"]);?></p>
            <hr width="100%" size="1" />
            <form name="form3" method="post" action="">
              <input name="id" type="hidden" id="id" value="<?php echo $row_RecProduct["productid"];?>">
              <input name="name" type="hidden" id="name" value="<?php echo $row_RecProduct["productname"];?>">
              <input name="price" type="hidden" id="price" value="<?php echo $row_RecProduct["productprice"];?>">
              <input name="qty" type="hidden" id="qty" value="1">
              <input name="cartaction" type="hidden" id="cartaction" value="add">
<input type="submit" name="button3" id="button3" value="加入購物車">&nbsp;
              <input type="button" name="button4" id="button4" value="回上一頁" onClick="window.history.back();">
            </form>
          </div>
			</div>		  
			
				<div class="divcomment">		 
				<p class="title"> 產品評價</p>	

				<?php if(($RecComment->num_rows)==0){?>
				<p>目前尚無評價</p>
				<?php }else{?>
					<table>
					<?php while($row_RecComment=$RecComment->fetch_assoc()){?>
					<tr>
					<td class="content">
					<?php
					
					$cusername=$row_RecComment["c_username"];
					$encusername=substr($cusername,0,3);
					$encusername.="***";
					$encusername.=substr(strrev($cusername),0,1);
					
					?>
					<p class="enusername" ><?php echo $encusername;?></p>
					<p ><?php echo $row_RecComment["c_description"];?></p>
					<p class="date_time"><?php echo $row_RecComment["c_datetime"];?></p>
					</td></tr>
				
					
					<?php }?>
				</table>
	  <?php }?>
		</div>
			



		</td>

	</tr>
	<tr>
		<td class="box-tiny">
	<div class="regbox categorybox">
              <h1>產品目錄 </h1>
              <ul >
                <li ><a href="product.php">所有產品 <span class="categorycount">(<?php echo $row_RecTotal["totalNum"];?>)</span></a></li>
                <?php	while($row_RecCategory=$RecCategory->fetch_assoc()){ ?>
                <li ><a href="product.php?cid=<?php echo $row_RecCategory["categoryid"];?>"><?php echo $row_RecCategory["categoryname"];?> <span class="categorycount">(<?php echo $row_RecCategory["productNum"];?>)</span></a></li>
                <?php }?>
              </ul>
            </div>
		</td>
	</tr>
</table>
</div>

<div align="center"  class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>

</body>
</html>
<?php
$stmt->close();
$stmt2->close();
$db_link->close();
?>