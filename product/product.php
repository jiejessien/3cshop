<?php
require_once("../connMysql.php");
//預設每頁筆數
$pageRow_records = 8;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
$query_RecProduct="SELECT * FROM product";
//若有分類關鍵字時未加限制顯示筆數的SQL敘述句

if(isset($_GET["cid"])&&($_GET["cid"]!="")){
	$query_RecProduct .= " WHERE (categoryid={$_GET["cid"]})";
//若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
}

if(isset($_GET["price1"])||isset($_GET["price2"])){
	if(strpos($query_RecProduct,"WHERE") == false)
		$query_RecProduct .=" WHERE";
	else
		$query_RecProduct .=" AND";
	if($_GET["price1"]!="")
		$minprice=$_GET["price1"];
	else
		$minprice=0;
	if($_GET["price2"]!="")
		$maxprice=$_GET["price2"];
	else
		$maxprice=99999999;
	$query_RecProduct .=" (productprice BETWEEN {$minprice} AND {$maxprice})";
}
if(isset($_GET["keyword"])&&($_GET["keyword"]!="")){
	if(strpos($query_RecProduct,"WHERE") == false)
		$query_RecProduct .=" WHERE";
	else
		$query_RecProduct .=" AND";
	$query_RecProduct .=" ((productname LIKE '%{$_GET["keyword"]}%')OR(description LIKE '%{$_GET["keyword"]}%'))";
}	

$query_RecProduct .=" ORDER BY productid DESC";

$all_RecProduct=$db_link->query($query_RecProduct);
//計算總筆數
$total_records = $all_RecProduct->num_rows;

//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
//返回 URL 參數
function keepURL(){
	$keepURL = "";
	if(isset($_GET["keyword"])) $keepURL.="&keyword=".urlencode($_GET["keyword"]);
	if(isset($_GET["price1"])) $keepURL.="&price1=".$_GET["price1"];
	if(isset($_GET["price2"])) $keepURL.="&price2=".$_GET["price2"];	
	if(isset($_GET["cid"])) $keepURL.="&cid=".$_GET["cid"];
	return $keepURL;
}
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
                  <input name="keyword" type="text" id="keyword" 
				  value="<?php if(isset($_GET["keyword"])) echo $_GET["keyword"];?>" placeholder="請輸入關鍵字" size="12" onClick="this.value='';">
                  
                </p>
              
              <h1> 價格區間 </h1>
              
                <p>
                  <input name="price1" type="text" id="price1" 
				  value="<?php if(isset($_GET["price1"])) echo $_GET["price1"];?>" placeholder="最低" size="5">
                  -
                  <input name="price2" type="text" id="price2" 
				  value="<?php if(isset($_GET["price2"])) echo $_GET["price2"];?>" placeholder="最高" size="8">
				  
				<br/><input type="submit" id="button" value="查詢">
                </p>
              </form>
            </div>
		</td>
		<td rowspan="2" class="box-large top" >
		
		
		
		     <?php
			 if(isset($_GET["cid"])&&($_GET["cid"]!="")){
				$query_RecCateName="SELECT categoryname FROM category WHERE categoryid=?";
				$stmt=$db_link->prepare($query_RecCateName);
				$stmt->bind_param("i",$_GET["cid"]);
				$stmt->execute();
				$RecCateName=$stmt->get_result();
				$row_RecCateName=$RecCateName->fetch_assoc();?>
				<p class="title"><?php echo $row_RecCateName["categoryname"];?></p>
			<?php	
			 }
			 else{?>
			 <p class="title">所有產品</p>
			 
			<?php }?>
			<div class="allalbum" >
			<?php
            //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
            $query_limit_RecProduct = $query_RecProduct." LIMIT {$startRow_records}, {$pageRow_records}";
			$RecProduct=$db_link->query($query_limit_RecProduct);
			
            while($row_RecProduct=$RecProduct->fetch_assoc()){ 
            ?>
            <div class="albumDiv ">
              <div class="picDiv" ><a href="productdetail.php?id=<?php echo $row_RecProduct["productid"];?>">
                <?php if($row_RecProduct["productimages"]==""){?>
                <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                <?php }else{?>
                <img src="proimg/<?php echo $row_RecProduct["productimages"];?>" alt="<?php echo $row_RecProduct["productname"];?>" width="135" height="135" border="0" />
                <?php }?>
                </a></div>
              <div class="albuminfo"><a href="productdetail.php?id=<?php echo $row_RecProduct["productid"];?>"><?php echo substr($row_RecProduct["productname"],0,29);?></a><br />
                <span class="smalltext">特價 </span><span class="redword"><?php echo $row_RecProduct["productprice"];?></span><span class="smalltext"> 元</span> </div>
            </div>
			 <?php }?>
			 </div>
			<div class="pageDiv">
                <?php  //分頁導覽，當總頁數>=5，只顯示5個頁數
				include("../pagenav.php");?>
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

$db_link->close();
?>