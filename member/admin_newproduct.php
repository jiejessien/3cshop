<?php 
function GetSQLValueString($theValue, $theType) {
  switch ($theType) {
    case "string":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_MAGIC_QUOTES) : "";
      break;
    case "int":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_SANITIZE_NUMBER_INT) : "";
      break;
    case "email":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_EMAIL) : "";
      break;
    case "url":
      $theValue = ($theValue != "") ? filter_var($theValue, FILTER_VALIDATE_URL) : "";
      break;      
  }
  return $theValue;
}


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

//商品分類資料表
$query_category="SELECT * FROM category";
$category=$db_link->query($query_category);

//送出表單新增
if(isset($_POST["action"])&&($_POST["action"]=="add")){
	for($i=0;$i<count($_FILES["productimages"]["name"]);$i++){
		if($_FILES["productimages"]["tmp_name"][$i] !=""){
			//接收到新分類名稱
			if(isset($_POST["newCateName"][$i])&&($_POST["newCateName"][$i]!="")){
				//新分類存入category
				$query_addCate="insert into category(categoryname) VALUES(?)";
				$stmt=$db_link->prepare($query_addCate);
				$stmt->bind_param(s,
				GetSQLValueString($_POST["newCateName"][$i],"string"));
				$stmt->execute();
				$categoryid=$stmt->insert_id;
				$stmt->close();
				$query_addCate="UPDATE category SET categorysort={$categoryid} where categoryid={$categoryid}";
				$db_link->query($query_addCate);
			}
			else{		
				//選現有的分類，從分類名稱取得分類id
				$query_get_categoryid="SELECT categoryid FROM category
				WHERE categoryname=?";
				$stmt=$db_link->prepare($query_get_categoryid);
				$stmt->bind_param("s",
				GetSQLValueString($_POST["productcategory"][$i],"string"));
				$stmt->execute();
				$stmt->bind_result($col1);
				$stmt->fetch();
				$categoryid=$col1;
				$stmt->close();
			}
			
			//將表單內容存入product資料表
			$query_insert="INSERT into product (categoryid,productname,productprice,productimages,description,producttime) 
			VALUES(?,?,?,?,?,NOW())";
			$stmt=$db_link->prepare($query_insert);
			$stmt->bind_param("isiss",
			$categoryid,
			GetSQLValueString($_POST["productname"][$i],"string"),
			GetSQLValueString($_POST["productprice"][$i],"int"),
			GetSQLValueString($_FILES["productimages"]["name"][$i],"string"),
			GetSQLValueString($_POST["description"][$i],"string"));
			$stmt->execute();
			if(!move_uploaded_file($_FILES["productimages"]["tmp_name"][$i],
			"../product/proimg/".$_FILES["productimages"]["name"][$i])) die("檔案上傳失敗");
			$stmt->close();
		}
	}
	header("Location:admin_productdata.php");
}


?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<link href="../css/catalogstyle.css" rel="stylesheet" type="text/css" >
<style>
.hidden{
	display:none;
}
.toggle{
	display:initial;
	margin-left:70px;
}
</style>
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
  
  $("select").change(function(){
	  //新增分類時，顯示輸入新分類名稱的欄位
	  if($(this).val()=='newCate'){
		  $(this).parents(".itemDiv").find(".hidden").addClass("toggle");
	  } 
	  else{
		  //不選時，清除新分類名稱欄位，並隱藏
		  $(this).parents(".itemDiv").find(".hidden").children().val("");
		  $(this).parents(".itemDiv").find(".hidden").removeClass("toggle");
	  }
  });
});
</script>
</head>

<body>

<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>
<div class="box-large">
<p class="title">新增商品</p>
<div class="content" > 
		<form action="admin_newproduct.php" name="newproduct" method="post" enctype="multipart/form-data">
		

		<?php 
		//列出五組新增表單
		for($i=0;$i<5;$i++){?>
		<div class="itemDiv">
		<h1>商品<?php echo $i+1;?></h1>
		<p>商品名稱：
		<input type="text" name="productname[]"></p>
		<p>分類　　：
		<select name="productcategory[]" id="productcategory[]">
		
			<?php
			//所有分類名稱選單
			$category->data_seek(0);
			while($row_category=$category->fetch_assoc()){?>
			<option value="<?php echo $row_category["categoryname"];?>"><?php echo $row_category["categoryname"];?></option>
			<?php }?>
			<option value="newCate" id="newCate[]">新增分類</option>
		</select></p>
		<p class="hidden" >新分類名稱：
		<input type="text" name="newCateName[]" id="newCateName[]" style="width:20%"></p>
		<p>價格　　：
		<input type="text" name="productprice[]"></p>
		<p>圖片上傳：
		<input type="file" name="productimages[]"></p>
		<p>商品敘述：
		<textarea rows="10" cols="40" name="description[]"></textarea></p>
		</div>
		<?php }?>
		<div class="btnrow">
			<hr size="1" />
			<input name="action" type="hidden" id="action" value="add">  
			<input type="submit" name="Submit2" value="送出申請">&nbsp
            <input type="reset" name="Submit3" value="重設資料">&nbsp
			<a class="button" href='admin_productdata.php'>回到管理商品</a>
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







