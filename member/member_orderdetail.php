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
if(!isset($_GET["orderid"])||($_GET["orderid"]=="")){
	header("Location: member_center.php");
}
$musername=$_SESSION["loginMember"];
//繫結登入會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_username = '{$_SESSION["loginMember"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();

//繫結訂單資訊
$orderid=$_GET["orderid"];
$qeury_RecOrder_Detail = "SELECT * FROM orders JOIN orderdetail ON orders.orderid=orderdetail.orderid
WHERE orderdetail.orderid={$orderid} ORDER BY orderdetail.orderid ASC";
$RecOrder_Detail=$db_link->query($qeury_RecOrder_Detail);
$row_RecOrder_Detail=$RecOrder_Detail->fetch_assoc();
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-3.5.0.min.js"></script>
<script>
$(document).ready(function(){
  $("table.table-list").find("tr:odd").css("background-color", "white");
  $("table.table-list").find("tr:even").css("background-color", "E9E9E2");
});
</script>

</head>

<body>
<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>
<div class="box-large">
<p class="title">訂單細節</p>
<div class="content" > 
	<table >
			<tr>
				<th width="30%"><label>訂購時間：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["datetime"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單編號：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["orderid"] ?></p></td>
			</tr>
						<tr>
				<th width="30%"><label>付款方式：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["paytype"] ?></p></td>
			</tr>
			
			<tr>
				<th width="30%"><label>訂單帳號：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["o_username"] ?></p></td>
			</tr>
			</table>
			<hr size="1" />


		<table >
			
			<tr>
				<th width="30%"><label>訂購人姓名：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["customername"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂購人電話：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["customerphone"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂購人住址：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["customeraddress"] ?></p></td>
			</tr>
            </table>
			<hr size="1" />


		<table >
			<tr>
				<th width="30%"><label>商品總額：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["total"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單運費：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["deliverfee"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單金額：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder_Detail["grandtotal"] ?></p></td>
			</tr>
          </table>          
          <hr size="1" />
		  
		
		<table >
			<tr>
				<th width="30%"><label>訂購商品：</label></th>
				<td width="70%">
					<table class="table-list" width="60%" border="0" cellpadding="2" cellspacing="1"  >
						<tr>
							<td width="70%" ><label>名稱</label></td>
							<td width="10%" ><label>數量</label></td>
							<td width="20%">&nbsp;</td>
						</tr>
						
						<?php
						$RecOrder_Detail->data_seek(0);
						while($row_RecOrderdetail=$RecOrder_Detail->fetch_assoc()){?>
						<tr>
							<td width="70%" ><p ><?php echo $row_RecOrderdetail["productname"] ?></p></td>
							<td width="10%" ><p><?php echo $row_RecOrderdetail["quantity"] ?></p></td>
							<td width="20%"><a href="member_comment.php?odid=<?php echo $row_RecOrderdetail["orderdetailid"];?>">評價商品</a></td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
			</table>
			
			<hr size="1" />
			
			<a class="button" href="member_center.php">回到訂單查詢</a>
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