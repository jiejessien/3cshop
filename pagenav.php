<?php 
 
function showpgindex($cur_p,$num_p){
	if($cur_p==$num_p)
		echo $cur_p;
	else{?>
		<a href="?page=<?php echo $cur_p;?>"><?php echo $cur_p;?></a>
<?php			
	}
}
?>
<a href="?page=<?php if ($num_pages > 1) {echo $num_pages-1;}
				  else{echo $num_pages;}?>">上一頁</a> 
                <?php 
				
				if($total_pages<=5){
					for($i=1;$i<=$total_pages;$i++){
						showpgindex($i,$num_pages);
					}
				}else{
					if($num_pages-2<=0){
						for($i=1;$i<=5;$i++){
						showpgindex($i,$num_pages);
						}
					}else if($num_pages+2>$total_pages){
						for($i=$total_pages-4;$i<=$total_pages;$i++){
						showpgindex($i,$num_pages);
						}
					}else{
						for($i=$num_pages-2;$i<=$num_pages+2;$i++){
						showpgindex($i,$num_pages);
						}
					}
				}
					?>
                  <a href="?page=<?php if ($num_pages < $total_pages) {echo $num_pages+1;}
				  else {echo $num_pages;}?>">下一頁</a> 