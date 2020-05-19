<?php 
require_once("../connMysql.php");

session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	header("Location: ../member/login.php");
}
//檢查權限是否足夠
if($_SESSION["memberLevel"]=="member"){
	header("Location: member_center.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: ../member/login.php");
}
if(!isset($_GET["orderid"])||($_GET["orderid"]=="")){
	header("Location: admin_center.php");
}

//繫結訂單資訊
$orderid=$_GET["orderid"];
$query_RecOrder = "SELECT * FROM orders WHERE orderid='{$orderid}'";
$RecOrder = $db_link->query($query_RecOrder);
$row_RecOrder=$RecOrder->fetch_assoc();
//繫結訂單細節
$query_RecOrderdetail = "SELECT * FROM orderdetail WHERE orderid='{$orderid}' ORDER BY orderdetailid ASC";
$RecOrderdetail = $db_link->query($query_RecOrderdetail);
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<link href="../css/catalogstyle.css" rel="stylesheet" type="text/css" >
<script src="../js/jquery-3.5.0.min.js"></script>
<script language="javascript">
function deletesure(){
    if (confirm('\n您確定要刪除這個會員嗎?\n刪除後無法恢復!\n')) return true;
    return false;
}
</script>
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
				<td width="70%"><p><?php echo $row_RecOrder["datetime"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單編號：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["orderid"] ?></p></td>
			</tr>
						<tr>
				<th width="30%"><label>付款方式：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["paytype"] ?></p></td>
			</tr>
			
			<tr>
				<th width="30%"><label>訂單帳號：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["o_username"] ?></p></td>
			</tr>
			</table>
<hr size="1" />

			<table >
			
			<tr>
				<th width="30%"><label>訂購人姓名：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["customername"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂購人電話：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["customerphone"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂購人住址：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["customeraddress"] ?></p></td>
			</tr>
            </table>
			<hr size="1" />


			<table >
			<tr>
				<th width="30%"><label>商品總額：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["total"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單運費：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["deliverfee"] ?></p></td>
			</tr>
			<tr>
				<th width="30%"><label>訂單金額：</label></th>
				<td width="70%"><p><?php echo $row_RecOrder["grandtotal"] ?></p></td>
			</tr>
          </table>          
          <hr size="1" />
		  
		
		<table >
			<tr>
				<th width="30%"><label>訂購商品：</label></th>
				<td width="70%">
					<table class="table-list" >
						<tr>
							<td width="45%" ><label>商品名稱</label></td>
							<td width="5%"><label>數量</label></td>
						</tr>
						<?php while($row_RecOrderdetail=$RecOrderdetail->fetch_assoc()){?>
						<tr>
							<td width="45%" ><p><?php echo $row_RecOrderdetail["productname"] ?></p></td>
							<td width="5%" ><p><?php echo $row_RecOrderdetail["quantity"] ?></p></td>
						</tr>
						<?php } ?>
					</table>
				</td>
			</tr>
			</table>
			<hr size="1" />
			<a class="button" href="admin_center.php">回到訂單查詢</a>
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