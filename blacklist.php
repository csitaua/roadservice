<?php

include 'dbc.php';
date_default_timezone_set('America/Aruba');
page_protect();
include "support/connect.php";
include "support/function.php";

if($_SESSION['user_level'] < RR_LEVEL){
	header("Location: index.php");
	exit();
}

$a_number = $_REQUEST['a_number'];
$comment = $_REQUEST['comment'];
$reason = $_REQUEST['reason'];
$clientno = $_REQUEST['clientno'];

if(strcmp($_REQUEST['submit'],'Submit')==0){
	if(strcmp(trim($a_number),'')==0){
	?>
		<script type="text/javascript">
			alert("Please enter License Plate");
		</script>	
	<?php
	}
	else if(strcmp(trim($comment),'')==0){
	?>
		<script type="text/javascript">
			alert("Comment cannot by empty");
		</script>	
	<?php
	}
	else{
		$time = date('n-j-Y G:i:s');
		$sql = "INSERT INTO `blacklist` (`a_number`,`timestamp`,`comment`, `user_id`, `reason`, `ClientNo`) VALUES ('$a_number', '$time', '$comment', '".$_SESSION['user_id']."', '$reason', '$clientno')  ";
		mysql_query($sql);
		
		$id = mysql_insert_id();
		
		 if (($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg" || $_FILES["image_upload_box"]["type"] == "image/gif" || $_FILES["image_upload_box"]["type"] == "image/x-png") && ($_FILES["image_upload_box"]["size"] < 4000000))
	{
		
  
		// some settings
		$max_upload_width = 900;
		$max_upload_height = 900;
		mkdir('blimage/'.$id);
		mkdir('blthumbs/'.$id);
		
		// if uploaded image was JPG/JPEG
		if($_FILES["image_upload_box"]["type"] == "image/jpeg" || $_FILES["image_upload_box"]["type"] == "image/pjpeg"){	
			$image_source = imagecreatefromjpeg($_FILES["image_upload_box"]["tmp_name"]);
		}		
		// if uploaded image was GIF
		if($_FILES["image_upload_box"]["type"] == "image/gif"){	
			$image_source = imagecreatefromgif($_FILES["image_upload_box"]["tmp_name"]);
		}	
		// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)	
		// if uploaded image was BMP
		if($_FILES["image_upload_box"]["type"] == "image/bmp"){	
			$image_source = imagecreatefromwbmp($_FILES["image_upload_box"]["tmp_name"]);
		}			
		// if uploaded image was PNG
		if($_FILES["image_upload_box"]["type"] == "image/x-png"){
			$image_source = imagecreatefrompng($_FILES["image_upload_box"]["tmp_name"]);
		}
		
		$ext = end(explode('.', $_FILES["image_upload_box"]["name"]));
		$fn = 1;
		$file_name = $id.'_'.$fn.'.'.$ext;
		$path = "blimage/".$id.'/';
		$patht = "blthumbs/".$id.'/'; //thumb
		$remote_file = $path.$file_name;
		$remote_file_t = $patht.$file_name; //thumbs
		while(file_exists($remote_file)){
			$fn++;
			$file_name = $id.'_'.$fn.'.'.$ext;
			$remote_file = $path.$file_name;	
			$remote_file_t = $patht.$file_name; //thumbs
		}
		
		
		imagejpeg($image_source,$remote_file,100);
		chmod($remote_file,0644);
		imagejpeg($image_source,$remote_file_t,100);
		chmod($remote_file_t,0644);
	

		// get width and height of original image
		list($image_width, $image_height) = getimagesize($remote_file);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			$proportions = $image_width/$image_height;
			
			if($image_width>$image_height){
				$new_width = $max_upload_width;
				$new_height = round($max_upload_width/$proportions);
			}		
			else{
				$new_height = $max_upload_height;
				$new_width = round($max_upload_height*$proportions);
			}		
			
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file,75);
			
			imagedestroy($new_image);
		}
		
		//*********************************Thumbs*************************************
		$max_upload_width = 120;
		$max_upload_height = 120;
		
		list($image_width, $image_height) = getimagesize($remote_file_t);
	
		if($image_width>$max_upload_width || $image_height >$max_upload_height){
			$proportions = $image_width/$image_height;
			
			if($image_width>$image_height){
				$new_width = $max_upload_width;
				$new_height = round($max_upload_width/$proportions);
			}		
			else{
				$new_height = $max_upload_height;
				$new_width = round($max_upload_height*$proportions);
			}		
			
			
			$new_image = imagecreatetruecolor($new_width , $new_height);
			$image_source = imagecreatefromjpeg($remote_file_t);
			
			imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
			imagejpeg($new_image,$remote_file_t,100);
			
			imagedestroy($new_image);
		}
		
		imagedestroy($image_source);
	}
		
		
		//header('Location: index.php');	
	}
}

session_start();


echo menu();

$col1 = 75;
$col2 = 275;

?>
	<table width="900">
    	<tr>
        	<td colspan="5" align="center" style="border:0;color:#148540"><h3>Blacklist</h3></td>
        </tr>
        <tr><td colspan="5">&nbsp;</td></tr>
        <form name="blacklist" enctype="multipart/form-data"  action="blacklist.php" method="post">
        <tr>
        	<td width="<?php echo $col1?>">License Plate:</td>
        	<td width="<?php echo $col2?>">
            	<input type="text" name="a_number" style="background-color:#FAD090"  size="8" value="<?php echo $a_number;?>"/>
  			</td>
       	</tr>
        <tr>
        	<td width="<?php echo $col1?>">Client No.:</td>
        	<td width="<?php echo $col2?>">
            	<input type="text" name="clientno" style="background-color:#FAD090"  size="8" value="<?php echo $clientno;?>"/>
  			</td>
       	</tr>
        <tr>
        	<td width="<?php echo $col1?>">Reason:</td>
            <td width="<?php echo $col2?>">
            	<select name="reason" style="background-color:#FAD090">
            	<?php
					$sql2 = "SELECT * FROM `blacklist_reasons` ORDER BY `reason` ASC";
					$rs2 = mysql_query($sql2);
					while($row2 = mysql_fetch_array($rs2)){
						if($row2['id'] == $reason){
							echo '<option selected="selected" value="'.$row2['id'].'">'.$row2['reason'].'</option>';
						}
						else{
							echo '<option value="'.$row2['id'].'">'.$row2['reason'].'</option>';	
						}
					}
				?>
                </select>
            </td>
        </tr>
         <tr>
        	<td width="<?php echo $col1?>">Comment:</td>
        	<td width="<?php echo $col2?>">
           		<textarea name="comment" style="background-color:#FAD090" ><?php echo $comment;?></textarea>
  			</td>
       	</tr>
        <tr>
       		<td width="<?php echo $col1?>">Image:</td>
            <td width="<?php echo $col2?>">
        		<input style="background-color:#FAD090" name="image_upload_box" type="file" id="image_upload_box" size="40" />
          	</td>
        </tr>
         <tr>
        	<td colspan="5"><input type="submit" name="submit" value="Submit"></td>
        </tr>
        </form>
        <tr>
 	</table>
</body>
</html>