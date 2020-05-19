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
//執行更新動作
if(isset($_POST["action"])&&($_POST["action"]=="update")){	
	$query_update = "UPDATE memberdata SET m_passwd=?, m_name=?, m_sex=?, m_birthday=?, m_email=?, m_phone=?, m_address=? WHERE m_id=?";
	$stmt = $db_link->prepare($query_update);
	//檢查是否有修改密碼
	$mpass = $_POST["m_passwdo"];
	if(($_POST["m_passwd"]!="")&&($_POST["m_passwd"]==$_POST["m_passwdrecheck"])){
		$mpass = password_hash($_POST["m_passwd"], PASSWORD_DEFAULT);
	}
	$stmt->bind_param("sssssssi", 
		$mpass,
		GetSQLValueString($_POST["m_name"], 'string'),
		GetSQLValueString($_POST["m_sex"], 'string'),		
		GetSQLValueString($_POST["m_birthday"], 'string'),
		GetSQLValueString($_POST["m_email"], 'email'),
		GetSQLValueString($_POST["m_phone"], 'string'),
		GetSQLValueString($_POST["m_address"], 'string'),		
		GetSQLValueString($_POST["m_id"], 'int'));
	$stmt->execute();
	$stmt->close();
		//重新導向
	header("Location: admin_memberdata.php");
}
//選取管理員資料
$query_RecAdmin = "SELECT * FROM memberdata WHERE m_username='{$_SESSION["loginMember"]}'";
$RecAdmin = $db_link->query($query_RecAdmin);	
$row_RecAdmin=$RecAdmin->fetch_assoc();
//繫結選取會員資料
$query_RecMember = "SELECT * FROM memberdata WHERE m_id='{$_GET["id"]}'";
$RecMember = $db_link->query($query_RecMember);	
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<link href="../css/catalogstyle.css" rel="stylesheet" type="text/css" >
<script language="javascript">
function checkForm(){
	if(document.formJoin.m_passwd.value!="" || document.formJoin.m_passwdrecheck.value!=""){
		if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
			document.formJoin.m_passwd.focus();
			return false;
		}
	}	
	if(document.formJoin.m_name.value==""){
		alert("請填寫姓名!");
		document.formJoin.m_name.focus();
		return false;
	}
	if(document.formJoin.m_birthday.value==""){
		alert("請填寫生日!");
		document.formJoin.m_birthday.focus();
		return false;
	}
	if(document.formJoin.m_email.value==""){
		alert("請填寫電子郵件!");
		document.formJoin.m_email.focus();
		return false;
	}
	if(document.formJoin.m_phone.value==""){
		alert("請填寫電話!");
		document.formJoin.m_phone.focus();
		return false;}
	if(document.formJoin.m_address.value==""){
		alert("請填寫住址!");
		document.formJoin.m_address.focus();
		return false;}
	if(!checkmail(document.formJoin.m_email)){
		document.formJoin.m_email.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;
	}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;
		}
		if(pw1.length<5 || pw1.length>16){
			alert( "密碼長度只能5到16個字母 !\n" );
			return false;
		}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;
		}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
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
  <li class="catalog-item "><a href="admin_memberdata.php">管理會員</a></li>
  <li class="catalog-item"><a href="admin_center.php">訂單查詢</a></li>
  <li class="catalog-item"><a href="admin_productdata.php">管理商品</a></li>
  <li class="catalog-item catalog-active"><a href="admin_update.php?id=<?php echo $mid;?>">修改個人資料</a></li>
</ul>
</div>

<form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
          

            <h1>帳號資料</h1>
			<div class="dataDiv">
            <label>使用帳號 ：</label><?php echo $row_RecMember["m_username"];?><br/>
            <label>使用密碼 ：</label>
            <input name="m_passwd" type="password" class="normalinput" id="m_passwd">
            <input name="m_passwdo" type="hidden" id="m_passwdo" value="<?php echo $row_RecMember["m_passwd"];?>"><br/>
            <label>確認密碼 ：</label>
            <input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck"><br/>
            <span class="smalltext">若不修改密碼，請不要填寫。若要修改，請輸入密碼二次。</span>
            <hr size="1" />
			</div>
			
            <h1>個人資料</h1>
			<div class="dataDiv">
            <label>真實姓名 ：</label>
            <input name="m_name" type="text" class="normalinput" id="m_name" value="<?php echo $row_RecMember["m_name"];?>">
            <span class="notice">*</span> <br/>
           <label>性　　別 ：</label>
            <input name="m_sex" type="radio" value="女" <?php if($row_RecMember["m_sex"]=="女") echo "checked";?>>女
            <input name="m_sex" type="radio" value="男" <?php if($row_RecMember["m_sex"]=="男") echo "checked";?>>男
            <br/>
            <label>生　　日 ：</label>
            <input name="m_birthday" type="text" class="normalinput" id="m_birthday" value="<?php echo $row_RecMember["m_birthday"];?>">
            <span class="notice">*</span><br/><span class="smalltext">為西元格式(YYYY-MM-DD)。 </span><br/>
            <label>電子郵件 ：</label>
            <input name="m_email" type="text" class="normalinput" id="m_email" value="<?php echo $row_RecMember["m_email"];?>">
            <span class="notice">*</span><br/><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span>
            <br/>
            <label>電　　話 ：</label>
            <input name="m_phone" type="text" class="normalinput" id="m_phone" value="<?php echo $row_RecMember["m_phone"];?>"> <span class="notice">*</span><br/>
            <label>住　　址 ：</label>
            <input name="m_address" type="text" class="normalinput" id="m_address" value="<?php echo $row_RecMember["m_address"];?>" size="40"> <span class="notice">*</span><br/>
            <p><span class="notice">*</span> 表示為必填的欄位</p>
          </div>
          <hr size="1" />
          <div class="btnrow">
            <input name="m_id" type="hidden" id="m_id" value="<?php echo $row_RecMember["m_id"];?>">
            <input name="action" type="hidden" id="action" value="update">
            <input type="submit" name="Submit2" value="修改資料">&nbsp
            <input type="reset" name="Submit3" value="重設資料">
            
          </div>
        </form> 
</div>

</html>
<?php
	$db_link->close();
?>