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
//刪除會員
if(isset($_GET["action"])&&($_GET["action"]=="delete")){
	$query_delMember = "DELETE FROM memberdata WHERE m_id=?";
	$stmt=$db_link->prepare($query_delMember);
	$stmt->bind_param("i", $_GET["id"]);
	$stmt->execute();
	$stmt->close();
	//重新導向回到主畫面
	header("Location: admin_memberdata.php");
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
$query_RecMember = "SELECT * FROM memberdata WHERE m_level<>'admin'";
//篩選姓名或帳號關鍵字
if(isset($_GET["keyword"])&&($_GET["keyword"]!="")){
	$query_RecMember.="AND ((m_username like '%{$_GET["keyword"]}%')OR(m_name like '%{$_GET["keyword"]}%'))";
}
$query_RecMember.=" ORDER BY m_jointime DESC";
//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
$query_limit_RecMember = $query_RecMember." LIMIT {$startRow_records}, {$pageRow_records}";
//以加上限制顯示筆數的SQL敘述句查詢資料到 $resultMember 中
$RecMember = $db_link->query($query_limit_RecMember);
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_resultMember 中
$all_RecMember = $db_link->query($query_RecMember);
//計算總筆數
$total_records = $all_RecMember->num_rows;
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
<script>
$(document).ready(function(){
  $("table.table-list").find("tr:even").css("background-color", "white");
  $("table.table-list").find("tr:odd").css("background-color", "E9E9E2");
});
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
  <li class="catalog-item catalog-active"><a href="admin_memberdata.php">管理會員</a></li>
  <li class="catalog-item"><a href="admin_center.php">訂單查詢</a></li>
  <li class="catalog-item"><a href="admin_productdata.php">管理商品</a></li>
  <li class="catalog-item"><a href="admin_update.php?id=<?php echo $mid;?>">修改個人資料</a></li>
</ul>
</div>
<div class="content" > 
	<form action="admin_memberdata.php" method="get">
	<input type="text" name="keyword" placeholder="請輸入會員姓名或帳號" >
	<input type="submit" value="搜尋">
	</form>
	<table class="table-list" >
		<tr>
			<th width="10%" >&nbsp;</th>
			<th width="20%" ><p>姓名</p></th>
			<th width="20%" ><p>帳號</p></th>
			<th width="20%" ><p>加入時間</p></th>
			<th width="20%" ><p>上次登入</p></th>
			<th width="10%" ><p>登入</p></th>
        </tr>
		<?php while($row_RecMember=$RecMember->fetch_assoc()){ ?>
        <tr>
          <td width="10%" ><p><a href="admin_update.php?id=<?php echo $row_RecMember["m_id"];?>">修改</a><br>
                <a href="?action=delete&id=<?php echo $row_RecMember["m_id"];?>" onClick="return deletesure();">刪除</a></p></td>
            <td width="20%" ><p><?php echo $row_RecMember["m_name"];?></p></td>
            <td width="20%" ><p><?php echo $row_RecMember["m_username"];?></p></td>
            <td width="20%" ><p><?php echo $row_RecMember["m_jointime"];?></p></td>
            <td width="20%" ><p><?php echo $row_RecMember["m_logintime"];?></p></td>
            <td width="10%" ><p><?php echo $row_RecMember["m_login"];?></p></td>
            </tr>
		<?php }?>
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