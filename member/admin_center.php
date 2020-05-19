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

//選取管理員資料
$query_RecAdmin = "SELECT m_id, m_name, m_logintime FROM memberdata WHERE m_username=?";
$stmt=$db_link->prepare($query_RecAdmin);
$stmt->bind_param("s", $_SESSION["loginMember"]);
$stmt->execute();
$stmt->bind_result($mid, $mname, $mlogintime);
$stmt->fetch();
$stmt->close();
//預設每頁筆數
$pageRow_records = 10;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
  $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages -1) * $pageRow_records;
//未加限制顯示筆數的SQL敘述句
$query_RecOrder = "SELECT * FROM orders";
//篩選日期後的query
if(isset($_GET["searchday"])&&($_GET["searchday"]!="")){
	$endday=date("Y-m-d",strtotime($_GET["searchday"]. "+3 month"));
	
	$query_RecOrder.=" WHERE datetime BETWEEN '{$_GET["searchday"]}' AND'{$endday}'";
}
$query_RecOrder.=" ORDER BY orderid DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecOrder = $query_RecOrder." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecOrder = $db_link->query($query_limit_RecOrder);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中

$all_RecOrder = $db_link->query($query_RecOrder);
//計算總筆數
$total_records = $all_RecOrder->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records/$pageRow_records);
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

</head>

<body>

<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>
<div class="box-large">
<div class="catalog">
<ul class="catalogbar">
  <li class="catalog-item"><a href="admin_memberdata.php">管理會員</a></li>
  <li class="catalog-item catalog-active"><a href="admin_center.php">訂單查詢</a></li>
  <li class="catalog-item"><a href="admin_productdata.php">管理商品</a></li>
  <li class="catalog-item"><a href="admin_update.php?id=<?php echo $mid;?>">修改個人資料</a></li>
</ul>
</div>
<div class="content" >

	<form action="admin_center.php" method="get">
	<label>搜尋選擇的日期往後三個月以內的訂單</label>
	<input type="date" name="searchday" placeholder="請選擇日期" value="<?php if(isset($_GET["searchday"]))echo $_GET["searchday"]; else echo date("Y-m-d",strtotime(date("Y-m-d"). "-3 month"));?>" max="<?php echo date("Y-m-d");?>">
	<input type="submit" value="搜尋">
	</form>   
    <table class="table-list" >
            <tr>
              <th width="10%"><p>訂單編號</th>
			  <th width="15%" ><p>訂購時間</th>
              <th width="12%" ><p>付款方式</p></th>
              <th width="13%" ><p>訂單金額</p></th>
              <th width="30%" ><p>商品名稱</p></th>
              <th width="8%" ><p>數量</p></th>
              <th width="12%" ><p>訂單細節</p></th>
            </tr>
			<?php while($row_RecOrder=$RecOrder->fetch_assoc()){
				$query_RecOrderdetail="SELECT orders.orderid AS orderid,productname,quantity
				FROM orders JOIN orderdetail ON orders.orderid=orderdetail.orderid
				WHERE orderdetail.orderid={$row_RecOrder['orderid']}";
				$RecOrderdetail=$db_link->query($query_RecOrderdetail);
				$num_rows=$RecOrderdetail->num_rows; ?>
					
            <tr>
              <td width="10%" rowspan="<?php echo $num_rows;?>"><p><?php echo $row_RecOrder["orderid"]?></p></td>
			  <td width="15%" rowspan="<?php echo $num_rows;?>"><p><?php echo $row_RecOrder["datetime"]?></p></td>
              <td width="12%" rowspan="<?php echo $num_rows;?>"><p><?php echo $row_RecOrder["paytype"]?></p></td>
              <td width="13%" rowspan="<?php echo $num_rows;?>"><p><?php echo $row_RecOrder["total"]?></p></td>
			  <?php 
				$row_RecOrderdetail=$RecOrderdetail->fetch_assoc();?>
			  <td width="30%" ><p><?php echo $row_RecOrderdetail["productname"]?></p></td>
			  <td width="8%" ><p><?php echo $row_RecOrderdetail["quantity"]?></p></td>
              <td width="12%"  rowspan="<?php echo $num_rows;?>"><p><a href="admin_orderdetail.php?orderid=<?php echo $row_RecOrder["orderid"] ;?>">查看明細</a></p></td>
			</tr>
			<?php
					while($row_RecOrderdetail=$RecOrderdetail->fetch_assoc()){?>
					<tr>
						<td width="30%" ><p><?php echo $row_RecOrderdetail["productname"]?></p></td>
						<td width="8%" ><p><?php echo $row_RecOrderdetail["quantity"]?></p></td>
					</tr>
						
			<?php  }
			  }?>
          </table> 
	<hr size="1" />
          <table class="pageDiv">
            <tr>
              <td ><p>資料筆數：<?php echo $total_records;?></p></td>
              <td ><p>
                <?php  //分頁導覽，當總頁數>=5，只顯示5個頁數
				include("../pagenav.php");?>
              </p></td>
            </tr>
        </table>



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