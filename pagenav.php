<?php 
if(strpos($_SERVER['REQUEST_URI'],'page='))
	$urlrem=substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'page=')+5);
else if(strpos($_SERVER['REQUEST_URI'],'?'))
	$urlrem=$_SERVER['REQUEST_URI'].'&page=';
else
	$urlrem=$_SERVER['REQUEST_URI'].'?page=';
$locationurl='http://';
$locationurl.=$_SERVER['HTTP_HOST'];
$locationurl.=$urlrem;

function showpgindex($cur_p,$num_p,$loc_url){
	if($cur_p==$num_p)
		echo $cur_p;
	else{?>
		<a href="<?php echo $loc_url.$cur_p;?>"><?php echo $cur_p;?></a>
<?php			
	}
}
?>
<a href="<?php if ($num_pages > 1) {echo $locationurl.$num_pages-1;}
				  else{echo $locationurl.$num_pages;}?>">上一頁</a> 
                <?php 
				
				if($total_pages<=5){
					for($i=1;$i<=$total_pages;$i++){
						showpgindex($i,$num_pages,$locationurl);
					}
				}else{
					if($num_pages-2<=0){
						for($i=1;$i<=5;$i++){
						showpgindex($i,$num_pages,$locationurl);
						}
					}else if($num_pages+2>$total_pages){
						for($i=$total_pages-4;$i<=$total_pages;$i++){
						showpgindex($i,$num_pages,$locationurl);
						}
					}else{
						for($i=$num_pages-2;$i<=$num_pages+2;$i++){
						showpgindex($i,$num_pages,$locationurl);
						}
					}
				}
					?>
                  <a href="<?php if ($num_pages < $total_pages) {echo $locationurl.$num_pages+1;}
				  else {echo $locationurl.$num_pages;}?>">下一頁</a> 