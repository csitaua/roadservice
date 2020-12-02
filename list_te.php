<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";
session_start();

$page = $_REQUEST['page'];
if(!$page){
	$page = 1;	
}

$paging = 30; //items per page
$page_dif = 5;
$col1 = 50;
$col2 = 130;
$col3 = 90;

echo menu();



?>

<table width="900">
    	<tr>
        	<td colspan="7" align="center" style="border:0;color:#148540"><h3>Travel Emergency</h3></td>
        </tr>
        <tr><td colspan="7" >&nbsp;</td></tr>
        <tr>
        	<td width="<?php echo $col1;?>">ID</td>
            <td width="<?php echo $col2;?>">Date</td>
            <td width="<?php echo $col2+15;?>">Name</td>
            <td width="<?php echo $col2+15;?>">Emergency</td>
            <td width="<?php echo $col3;?>">Country</td>
            <td width="<?php echo $col2;?>">Phone1</td>
            <td>Phone2</td>
        </tr>  
        <?php
			$sql = "SELECT * FROM travel_req order by STR_TO_DATE( `datetime` , '%m-%d-%Y' ) DESC, id DESC";
			$rs = mysql_query($sql);
			$num_rows = mysql_num_rows($rs);
			$end = ($paging * $page)-1;
			$current_pt = ($paging * $page) - $paging;
			$sql = "SELECT * FROM travel_req order by STR_TO_DATE( `datetime` , '%m-%d-%Y' ) DESC, id DESC LIMIT ".$current_pt.", ".$paging;
			$rs = mysql_query($sql);
			$rc = 1;
			while($row = mysql_fetch_array($rs)){
				
		?> 
        	<tr>
                <td width="<?php echo $col1;?>"><a style="color:#DCB272" href="edit_te.php?te=<?php echo $row['id'];?>"/><?php echo str_pad($row['id'],5,'0',STR_PAD_LEFT);?></td>
               	<td width="<?php echo $col2;?>"><?php echo substr($row['datetime'],0,16);?></td>
               	<td width="<?php echo $col2+15;?>"><?php echo $row['name'];?></td>
                <td width="<?php echo $col2+15;?>"><?php 
					$eid = $row['em'];
					$sql2 = "SELECT * FROM emergency where id = '$eid'";
					$rs2 = mysql_query($sql2);
					$row2 = mysql_fetch_array($rs2);
					echo $row2['description'];
				?></td>
                <td width="<?php echo $col3;?>"><?php echo $row['country'];?></td>
              	<td width="<?php echo $col2;?>"><?php echo $row['phone1'];?></td>
                <td ><?php echo $row['phone2'];?></td>
        	</tr> 
        <?php
			$current_pt++;
			if($rc){
				$rc=0;	
			}
			else{
				$rc=1;	
			}
			}
		?>
        <tr><td colspan="7">&nbsp;</td></tr>
        <tr>
        	<td colspan="7">Page 
            	<?php
					$cur_page = $page;
					if($page - $page_dif < 1){
						$start_page = 1;	
					}
					else{
						$start_page = $page - $page_dif;	
						$less = $start_page - 1;
					}
					
					while($start_page < ($page + $page_dif) && ($num_rows > (($start_page-1)*$paging))){ //add pages
						
						if($start_page == ($page + $page_dif - 1)){
							echo '<a style="color:#DCB272" href="/roadservice/list_te.php?page='.$start_page.'">'.$start_page.'</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.($start_page+1).'"> More</a>';
						}
						else if($page == $start_page){
							echo '<<a style="color:#DCB272; font-weight: bold" href="/roadservice/list_te.php?page='.$start_page.'">'.$start_page.'</a>> , ';
						}
						else if ($less > 1){
							echo '<a style="color:#DCB272" href="/roadservice/list_te.php?page='.($less).'"> Less</a> , <a style="color:#DCB272" href="/roadservice/index.php?page='.$start_page.'">'.$start_page.'</a> , ';
							$less = 0;
						}
						else{
							echo '<a style="color:#DCB272" href="/roadservice/list_te.php?page='.$start_page.'">'.$start_page.'</a> , ';
						}
						$start_page++;
					}
				?>
            </td>
        </tr>
 </table>

</body>
</html>