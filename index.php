
<html>
<head>
<meta charset="utf-8" />
<title>3C購物網</title>
<link href="css/product_style.css" rel="stylesheet" type="text/css">
<link href="css/nav_style.css" rel="stylesheet" type="text/css">
<script src="../js/jquery-3.5.0.min.js"></script>
<script>
$(document).ready(function(){
  $("body").find("*").css("font-family", "微軟正黑體");

});
</script>
</head>


<body>
<!--nav class="navbar"-->
	<?php //上方導覽列
	//include("navbar.html"); ?>
</nav>
<div class="box-primary ">
<p>未完成的首頁</p>
<p>請點選商品瀏覽或會員系統</p>
<ul>
	<li><a href="product/product.php">商品瀏覽</a></li>
	<li><a href="member/login.php">會員系統</a></li>
</ul>

</div>
<div align="center"  class="trademark">
	<hr/>
	<p>&copy <?php echo date("Y"); ?> 3CShop</p>

</div>


</body>
</html>
