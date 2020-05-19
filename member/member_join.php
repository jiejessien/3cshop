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

if(isset($_POST["action"])&&($_POST["action"]=="join")){
	require_once("../connMysql.php");
	//找尋帳號是否已經註冊
	$query_RecFindUser = "SELECT m_username FROM memberdata WHERE m_username='{$_POST["m_username"]}'";
	$RecFindUser=$db_link->query($query_RecFindUser);
	if ($RecFindUser->num_rows>0){
		header("Location: member_join.php?errMsg=1&username={$_POST["m_username"]}");
	}else{
	//若沒有執行新增的動作	
		$query_insert = "INSERT INTO memberdata (m_name, m_username, m_passwd, m_sex, m_birthday, m_email, m_phone, m_address, m_jointime) 
		VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
		$stmt = $db_link->prepare($query_insert);
		$stmt->bind_param("ssssssss", 
			GetSQLValueString($_POST["m_name"], "string"),
			GetSQLValueString($_POST["m_username"], 'string'),
			password_hash($_POST["m_passwd"], PASSWORD_DEFAULT),
			GetSQLValueString($_POST["m_sex"], 'string'),
			GetSQLValueString($_POST["m_birthday"], 'string'),
			GetSQLValueString($_POST["m_email"], 'email'),
			GetSQLValueString($_POST["m_phone"], 'string'),
			GetSQLValueString($_POST["m_address"], 'string'));
		$stmt->execute();
		$stmt->close();
		$db_link->close();
		header("Location: member_join.php?loginStats=1");
	}
}
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網會員系統</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.m_username.value==""){		
		alert("請填寫帳號!");
		document.formJoin.m_username.focus();
		return false;
	}else{
		uid=document.formJoin.m_username.value;
		if(uid.length<5 || uid.length>16){
			alert( "您的帳號長度只能5至16個字元!" );
			document.formJoin.m_username.focus();
			return false;}
		if(!(uid.charAt(0)>='a' && uid.charAt(0)<='z')){
			alert("您的帳號第一字元只能為小寫字母!" );
			document.formJoin.m_username.focus();
			return false;}
		for(idx=0;idx<uid.length;idx++){
			if(uid.charAt(idx)>='A'&&uid.charAt(idx)<='Z'){
				alert("帳號不可以含有大寫字元!" );
				document.formJoin.m_username.focus();
				return false;}
			if(!(( uid.charAt(idx)>='a'&&uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9')||( uid.charAt(idx)=='_'))){
				alert( "您的帳號只能是數字,英文字母及「_」等符號,其他的符號都不能使用!" );
				document.formJoin.m_username.focus();
				return false;}
			if(uid.charAt(idx)=='_'&&uid.charAt(idx-1)=='_'){
				alert( "「_」符號不可相連 !\n" );
				document.formJoin.m_username.focus();
				return false;}
		}
	}
	if(!check_passwd(document.formJoin.m_passwd.value,document.formJoin.m_passwdrecheck.value)){
		document.formJoin.m_passwd.focus();
		return false;}	
	if(document.formJoin.m_name.value==""){
		alert("請填寫姓名!");
		document.formJoin.m_name.focus();
		return false;}
	if(document.formJoin.m_birthday.value==""){
		alert("請填寫生日!");
		document.formJoin.m_birthday.focus();
		return false;}
	if(document.formJoin.m_email.value==""){
		alert("請填寫電子郵件!");
		document.formJoin.m_email.focus();
		return false;}
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
		return false;}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;}
		if(pw1.length<5 || pw1.length>16){
			alert( "密碼長度只能5到10個字母 !\n" );
			return false;}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;}
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

<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('會員新增成功\n請用申請的帳號密碼登入。');
window.location.href='../member/login.php';		  
</script>
<?php }?>

<div class="box-large">
<p class="title">加入會員</p>
<div class="content" > 

<form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
		  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv">帳號 <?php echo $_GET["username"];?> 已經有人使用！</div>
          <?php }?>
         
            <h1>帳號資料</h1>
			 <div class="dataDiv">
            <label>使用帳號：</label>
            <input name="m_username" type="text" class="normalinput" id="m_username">
            <span class="notice">*</span> <br/><span class="smalltext">請填入5~16個字元以內的小寫英文字母、數字、以及_ 符號。</span><br/>
            <label>使用密碼：</label>
            <input name="m_passwd" type="password" class="normalinput" id="m_passwd">
            <span class="notice">*</span> <br/><span class="smalltext">請填入5~16個字元以內的小寫英文字母、數字、以及_ 符號。</span><br/>
            <label>確認密碼：</label>
            <input name="m_passwdrecheck" type="password" class="normalinput" id="m_passwdrecheck">
            <span class="notice">*</span> <br/><span class="smalltext">再輸入一次密碼</span><br/>
		</div>

            <h1>個人資料</h1>
		<div class="dataDiv">
            <label>真實姓名：</label>
            <input name="m_name" type="text" class="normalinput" id="m_name">
            <span class="notice">*</span> <br/>
            <label>性　　別：</label>
            <input name="m_sex" type="radio" value="女" checked>女
            <input name="m_sex" type="radio" value="男">男
            <br/>
            <label>生　　日：</label>
            <input name="m_birthday" type="text" class="normalinput" id="m_birthday">
            <span class="notice">*</span> <br/>
            <span class="smalltext">為西元格式(YYYY-MM-DD)。</span><br/>
            <label>電子郵件：</label>
            <input name="m_email" type="text" class="normalinput" id="m_email">
            <span class="notice">*</span> <br/><span class="smalltext">請確定此電子郵件為可使用狀態，以方便未來系統使用，如補寄會員密碼信。</span><br/>
           
            <label>電　　話：</label>
            <input name="m_phone" type="text" class="normalinput" id="m_phone"> <span class="notice">*</span><br/>
            <label>住　　址：</label>
            <input name="" type="text" class="normalinput" id="m_address" size="40"> <span class="notice">*</span><br/>
            <span class="notice">*</span> 表示為必填的欄位<br/> 
          </div>
          <hr size="1" />
          <div class="btnrow">
            <input name="action" type="hidden" id="action" value="join">
            <input type="submit" name="Submit2" value="送出申請">&nbsp
            <input type="reset" name="Submit3" value="重設資料">&nbsp
            <input type="button" name="Submit" value="回到登入會員" onClick="window.history.back();">
          </div>
        </form>

</div></div>

<div align="center"  class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>



</body>
</html>