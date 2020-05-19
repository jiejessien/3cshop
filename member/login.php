<?php
require_once("../connMysql.php");
session_start();
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["loginMember"]) && ($_SESSION["loginMember"]!="")){
	//若帳號等級為 member 則導向會員中心
	if($_SESSION["memberLevel"]=="member"){
		header("Location: member_center.php");
	//否則則導向管理中心
	}else{
		header("Location: admin_center.php");	
	}
}
//執行會員登入
if(isset($_POST["username"]) && isset($_POST["passwd"])){
	//繫結登入會員資料
	$query_RecLogin = "SELECT m_username, m_passwd, m_level FROM memberdata WHERE m_username=?";
	$stmt=$db_link->prepare($query_RecLogin);
	$stmt->bind_param("s", $_POST["username"]);
	$stmt->execute();
	//取出帳號密碼的值綁定結果
	$stmt->bind_result($username, $passwd, $level);	
	$stmt->fetch();
	$stmt->close();
	//比對密碼，若登入成功則呈現登入狀態
	if(password_verify($_POST["passwd"],$passwd)){
		//計算登入次數及更新登入時間
		$query_RecLoginUpdate = "UPDATE memberdata SET m_login=m_login+1, m_logintime=NOW() WHERE m_username=?";
		$stmt=$db_link->prepare($query_RecLoginUpdate);
	    $stmt->bind_param("s", $username);
	    $stmt->execute();	
	    $stmt->close();
		//設定登入者的名稱及等級
		$_SESSION["loginMember"]=$username;
		$_SESSION["memberLevel"]=$level;
		//使用Cookie記錄登入資料
		if(isset($_POST["rememberme"])&&($_POST["rememberme"]=="true")){
			setcookie("remUser", $_POST["username"], time()+365*24*60);
			setcookie("remPass", $_POST["passwd"], time()+365*24*60);
		}else{
			if(isset($_COOKIE["remUser"])){
				setcookie("remUser", $_POST["username"], time()-100);
				setcookie("remPass", $_POST["passwd"], time()-100);
			}
		}
		//使用購物車時未登入，登入後前往結帳
		if(isset($_SESSION["checkout"])&&($_SESSION["checkout"]=="true")){
			unset($_SESSION["checkout"]);
			header("Location: ../cart/checkout.php");
		}
		//若帳號等級為 member 則導向會員中心
		else if($_SESSION["memberLevel"]=="member"){
			header("Location: member_center.php");
		//否則則導向管理中心
		}else{
			header("Location: admin_memberdata.php");	
		}
	}else{
		header("Location: ../member/login.php?errMsg=1");
	}
}
?>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>3C購物網</title>
<link href="../css/member_style.css" rel="stylesheet" type="text/css">
<!--link href="../css/bootstrap.min.css" rel="stylesheet" /-->
<script src="../js/jquery-3.5.0.min.js"></script>
<!--script src="../js/bootstrap.min.js"></script-->


</head>

<body>


<nav class="navbar">
	<?php //上方導覽列
	include("../navbar.html"); ?>
</nav>


<div class="box-small">
<p class="title">會員系統</p>
<div class="content" >
<div class="regbox" >
       
<?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
          <div class="errDiv"> 登入帳號或密碼錯誤！</div>
          <?php }?>
          <h1>登入會員</h1>
          <form name="form1" method="post" action="">
              <input name="username" type="text"  id="username" placeholder="請輸入帳號" value="<?php if(isset($_COOKIE["remUser"]) && ($_COOKIE["remUser"]!="")) echo $_COOKIE["remUser"];?>">
			  <br /><br />
			  <input name="passwd" type="password"  id="passwd" placeholder="請輸入密碼" value="<?php if(isset($_COOKIE["remPass"]) && ($_COOKIE["remPass"]!="")) echo $_COOKIE["remPass"];?>">
              <p><input name="rememberme" type="checkbox" id="rememberme" value="true" checked>記住帳號密碼</p>
              <input type="submit" name="button" id="button" class="button" value="登入系統">
            </form>
          <h1>還沒有會員帳號嗎?</h1>
		  <a href="member_join.php">註冊會員</a>
		</div></div></div>


<div class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>


</body>
</html>
<?php
	$db_link->close();
?>
