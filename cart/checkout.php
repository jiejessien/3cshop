<?php
require_once("../connMysql.php");
//購物車開始
include("mycart.php");
session_start();
//檢查是否經過登入
if(!isset($_SESSION["loginMember"]) || ($_SESSION["loginMember"]=="")){
	$_SESSION["checkout"]="true";
	header("Location: ../member/login.php");
}
//執行登出動作
if(isset($_GET["logout"]) && ($_GET["logout"]=="true")){
	unset($_SESSION["loginMember"]);
	unset($_SESSION["memberLevel"]);
	header("Location: ../member/login.php");
}

$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
if(!is_object($cart)) $cart = new myCart();
//購物車結束
//繫結會員資料

$query_member="SELECT * FROM memberdata WHERE m_username='{$_SESSION["loginMember"]}'";
$RecMember=$db_link->query($query_member);
$row_RecMember=$RecMember->fetch_assoc();
?>
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網</title>
<link href="../css/cart_style.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-3.5.0.min.js"></script>
<script>
$(document).ready(function(){
  $("body").find("*").css("font-family", "微軟正黑體");
	$("#sameData").click(function(){
		$("#customername").val("<?php  echo $row_RecMember['m_name']; ?>");
		$("#customerphone").val("<?php echo $row_RecMember['m_phone']; ?>");
		$("#customeraddress").val("<?php  echo $row_RecMember['m_address']; ?>");
	});
});
</script>
<script language="javascript">
function checkForm(){	
	if(document.cartform.customername.value==""){
		alert("請填寫姓名!");
		document.cartform.customername.focus();
		return false;
	}
	if(document.cartform.customerphone.value==""){
		alert("請填寫電話!");
		document.cartform.customerphone.focus();
		return false;
	}
	if(document.cartform.customeraddress.value==""){
		alert("請填寫地址!");
		document.cartform.customeraddress.focus();
		return false;
	}
	return confirm('確定送出嗎？');
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
<p class="title">購物結帳</p>
<div class="content" >
 
            <div >
              <?php if($cart->itemcount > 0) {?>
              <h1> 購物內容</h1>
              <table >
                <tr>
                  <th width="10%"><label>編號</label></th>
                  <th width="50%"><label>產品名稱</label></th>
                  <th width="10%"><label>數量</label></th>
                  <th width="15%"><label>單價</label></th>
                  <th width="15%"><label>小計</label></th>
                </tr>
                <?php		  
		  	$i=0;
			foreach($cart->get_contents() as $item) {
			$i++;
		  ?>
                <tr class="table-product">
                  <td  ><p><?php echo $i;?>.</p></td>
                  <td  ><p><?php echo $item['info'];?></p></td>
                  <td ><p><?php echo $item['qty'];?></p></td>
                  <td ><p>$ <?php echo number_format($item['price']);?></p></td>
                  <td ><p>$ <?php echo number_format($item['subtotal']);?></p></td>
                </tr>
                <?php }?>
                <tr>
                  <td ><label>運費</label></td>
                  <td><p>&nbsp;</p></td>
                  <td><p>&nbsp;</p></td>
                  <td ><p>&nbsp;</p></td>
                  <td ><p>$ <?php echo number_format($cart->deliverfee);?></p></td>
                </tr>
                <tr>
                  <td ><label>總計</label></td>
                  <td><p>&nbsp;</p></td>
                  <td ><p>&nbsp;</p></td>
                  <td ><p>&nbsp;</p></td>
                  <td ><p class="redword">$ <?php echo number_format($cart->grandtotal);?></p></td>
                </tr>
              </table>
              <hr width="100%" size="1" />
              <h1> 訂購資訊</h1>
              <form action="cartreport.php" method="post" name="cartform" id="cartform" onSubmit="return checkForm();">
			  <input type="checkbox" id="sameData" value="同會員資訊">
			  <label>同會員資訊</label>
                <table >
                  <tr>
                    <th width="20%" ><label>姓名</label></th>
                    <td ><p>
                        <input type="text" name="customername" id="customername">
                        <span class="notice">*</span></p></td>
                  </tr>
                  
                  <tr>
                    <th width="20%" ><label>電話</label></th>
                    <td ><p>
                        <input type="text" name="customerphone" id="customerphone">
                        <span class="notice">*</span></p></td>
                  </tr>
                  <tr>
                    <th width="20%" ><label>住址</label></th>
                    <td ><p>
                        <input name="customeraddress" type="text" id="customeraddress" size="40">
                        <span class="notice">*</span></p></td>
                  </tr>
                  <tr>
                    <th width="20%" ><label>付款方式</label></th>
                    <td ><p>
                        <select name="paytype" id="paytype">
                          <option value="ATM匯款" selected>ATM匯款</option>
                          <option value="線上刷卡">線上刷卡</option>
                          <option value="貨到付款">貨到付款</option>
                        </select>
                      </p></td>
                  </tr>
                  <tr>
                    <td colspan="2" ><p><span class="notice">*</span> 表示為必填的欄位</p></td>
                  </tr>
                </table>
                <hr width="100%" size="1" />
                <div class="btnrow">
                  <input name="cartaction" type="hidden" id="cartaction" value="update">
                  <input type="submit" name="updatebtn" id="button3" value="送出訂購單">&nbsp;
                  <input type="button" name="backbtn" id="button4" value="回上一頁" onClick="window.history.back();">
                </div>
              </form>
            </div>
            <?php }else{ ?>
            <div class="infoDiv">目前購物車是空的。</div>
            <?php } ?>

</div></div>
<div align="center"  class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>

</body>
</html>
<?php $db_link->close();?>