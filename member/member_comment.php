<?php
require_once("../connMysql.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: ../member/login.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: ../member/login.php");
}
//是否接收orderdetailid
if(!isset($_GET["odid"])||($_GET["odid"]=="")){
	header("Location: member_center.php");
}
//如果不是自己的訂單_回到會員中心
$query_odid_user="SELECT * FROM orders JOIN orderdetail ON orders.orderid=orderdetail.orderid
WHERE orderdetail.orderdetailid={$_GET["odid"]} AND o_username='{$_SESSION["loginMember"]}'";
$odid_user=$db_link->query($query_odid_user);
if($odid_user->num_rows==0){
	header("Location: member_center.php");
}
//訂購商品的商品資訊
$query_product_od="SELECT * FROM product JOIN orderdetail ON product.productid=orderdetail.productid
WHERE orderdetailid={$_GET["odid"]}";
$product_od=$db_link->query($query_product_od);
$row_product_od=$product_od->fetch_assoc();
//query該orderdetailid在comment中的狀態
$query_comment_descr="SELECT * FROM comment WHERE c_orderdetailid={$_GET["odid"]}";
$comment_descr=$db_link->query($query_comment_descr);
if(isset($_POST["text_comment"])&&($_POST["text_comment"]!="")){
	//是否已有評價，修改評價
	if($row_comment_descr=$comment_descr->fetch_assoc()){
		$query_set_descr="UPDATE comment SET c_description='{$_POST["text_comment"]}',c_datetime=NOW() WHERE c_orderdetailid={$_GET["odid"]}";
		$set_descr=$db_link->query($query_set_descr);		
		header("Location: member_comment.php?odid={$_GET['odid']}&stats=1");
	}
	else{
	//insert新一筆評價至comment
	$query_insert="INSERT INTO comment (c_username,c_orderdetailid,c_productid,c_description,c_datetime)
	VALUES(?,?,?,?,NOW())";
	$stmt=$db_link->prepare($query_insert);
	$stmt->bind_param("siis",
	$_SESSION["loginMember"],
	$row_product_od["orderdetailid"],
	$row_product_od["productid"],
	$_POST["text_comment"]);
	$stmt->execute();
	$stmt->close();
	header("Location: member_comment.php?odid={$_GET['odid']}&stats=1");
	}
}
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-3.5.0.min.js"></script>
<script>
$(document).ready(function(){
  $("#recomm1").click(function (){
	  $("#text_comment").val(function(i,origVal){
		  return origVal+$("#recomm1").val();
	  });
  });
  $("#recomm2").click(function (){
	  $("#text_comment").val(function(i,origVal){
		  return origVal+$("#recomm2").val();
	  });
  });
  $("#recomm3").click(function (){
	  $("#text_comment").val(function(i,origVal){
		  return origVal+$("#recomm3").val();
	  });
  });
});

</script>
</head>

<body>
<?php if(isset($_GET["stats"]) && ($_GET["stats"]=="1")){?>
<script language="javascript">
alert('評價已送出'); 
</script>

<?php }?>
<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>
<div class="box-large">
<p class="title">評價商品</p>
<div class="content" > 
	<table>
		<tr>
				<th width="30%"><p>商品編號：</p></th>
				<td width="70%"><p><?php echo $row_product_od["productid"];?></p></td>
		</tr>
		<tr>
				<th width="30%"><p>商品名稱：</p></th>
				<td width="70%"><p><?php echo $row_product_od["productname"];?></p></td>
		</tr>
		<tr>
				<th width="30%"><p>我的評價：</p></th>
				<td width="70%">
					<form action="" method="POST" >
						<textarea name="text_comment" id="text_comment" rows="10" cols="40"><?php
						$comment_descr->data_seek(0);
						if($row_comment_descr=$comment_descr->fetch_assoc()){
						echo $row_comment_descr["c_description"];
						}
						?>
						</textarea>
				</td>
		</tr>			
	</table>
			<div class="btnrow">
			<label>快速評價</label>
			<input type="button" class="recomm" id="recomm1" value="品質良好!">&nbsp;
			<input type="button" class="recomm" id="recomm2" value="寄貨迅速!">&nbsp;
			<input type="button" class="recomm" id="recomm3" value="服務優良!"><br/>
			
			<input type="submit" name="Submit2" value="確定評價">&nbsp;
						
			<a class="button" href="member_orderdetail.php?orderid=<?php echo $row_product_od["orderid"];?>">回到訂單細節</a>
			</div>
					</form>		
</div></div>

<div class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>

</body>
</html>
<?php
	$db_link->close();
?>