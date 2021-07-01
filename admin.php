<?php
include 'dbc.php';
include 'support/function.php';
page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$page_limit = 10;
error_reporting(0);

$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$login_path = @ereg_replace('admin','',dirname($_SERVER['PHP_SELF']));
$path   = rtrim($login_path, '/\\');

// filter GET values
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

foreach($_POST as $key => $value) {
	$post[$key] = filter($value);
}

if($post['doBan'] == 'Ban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("update users set banned='1' where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

if($_POST['doUnban'] == 'Unban') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("update users set banned='0' where id='$id'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

if($_POST['doDelete'] == 'Delete') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("delete from users where id='$id' and `user_name` <> 'admin'");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

if($_POST['doApprove'] == 'Approve') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("update users set approved='1' where id='$id'");

	list($to_email) = mysql_fetch_row(mysql_query("select user_email from users where id='$uid'"));

$message =
"Hello,\n
Thank you for registering with us. Your account has been activated...\n

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE.
***DO NOT RESPOND TO THIS EMAIL****
";

@mail($to_email, "User Activation", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion());

	}
 }

 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];
 header("Location: $ret");
 exit();
}

$rs_all = mysql_query("select count(*) as total_all from users") or die(mysql_error());
$rs_active = mysql_query("select count(*) as total_active from users where approved='1'") or die(mysql_error());
$rs_total_pending = mysql_query("select count(*) as tot from users where approved='0'");

list($total_pending) = mysql_fetch_row($rs_total_pending);
list($all) = mysql_fetch_row($rs_all);
list($active) = mysql_fetch_row($rs_active);


?>
<html>
<head>
<title>Administration Main Page</title>

<?php echo adminMenu(); ?>

<header class="bg-white shadow">
	<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
		<h2 class="font-semibold text-2xl text-gray-800 leading-tight">
			 User Administration Page
	 </h2>
 </div>
</header>

<div class="py-12">
		<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-6 py-6">
      <table class="text-base table-fixed w-full">
        <tr>
          <td class="w-1/3">Total users: <?php echo $all;?></td>
          <td class="w-1/3">Active users: <?php echo $active; ?></td>
          <td class="w-2/9">Pending users: <?php echo $total_pending; ?></td>
					<td class="w-1/9">
						&nbsp;
					</td>

        </tr>
      </table>
      <p><?php
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
      <table class="text-base w-9/12 p-4" >
        <tr>
          <td><form name="form1" method="get" action="admin.php">
              <p align="center">Search
                <input name="q" id="q" value="<?php echo $_GET['q'];?>" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm">
                <br>
                [Type email or user name] </p>
              <p align="center">
                <input type="radio" name="qoption" value="pending">
                Pending users
                <input type="radio" name="qoption" value="recent">
                Recently registered
                <input type="radio" name="qoption" value="banned">
                Banned users <br>
                <br>
                [You can leave search blank to if you use above options]</p>
              <p align="center">
                <input name="doSearch" type="submit" id="doSearch2" value="Search" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
              </p>
              </form></td>
        </tr>
      </table>
      <p>
        <?php if ($get['doSearch'] == 'Search') {
	  $cond = '';
	  if($get['qoption'] == 'pending') {
	  $cond = "where `approved`='0' order by date desc";
	  }
	  if($get['qoption'] == 'recent') {
	  $cond = "order by date desc";
	  }
	  if($get['qoption'] == 'banned') {
	  $cond = "where `banned`='1' order by date desc";
	  }

	  if($get['q'] == '') {
	  $sql = "select * from users $cond";
	  }
	  else {
	  $sql = "select * from users where `user_email` LIKE '%$_REQUEST[q]%' or `user_name` LIKE '%$_REQUEST[q]%' ";
	  }


	  $rs_total = mysql_query($sql) or die(mysql_error());
	  $total = mysql_num_rows($rs_total);

	  if (!isset($_GET['page']) )
		{ $start=0; } else
		{ $start = ($_GET['page'] - 1) * $page_limit; }

	  $rs_results = mysql_query($sql . " limit $start,$page_limit") or die(mysql_error());
	  $total_pages = ceil($total/$page_limit);

	  ?>
      <p align="right">
    <?php
	  // outputting the pages
		if ($total > $_GET['page'] - 1)
		{
		echo "<div><strong>Pages:</strong> ";
		$i = 0;
		//while ($i < $page_limit)
		while ($i < $total_pages){
			$page_no = $i+1;
			$qstr = ereg_replace("&page=[0-9]+","",$_SERVER['QUERY_STRING']);
			echo "<a href=\"admin.php?$qstr&page=$page_no\">$page_no</a> ";
			$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin.php" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr class="bg-gray-400">
            <td width="4%"><strong>&nbsp;</strong></td>
            <td width="8%"><strong>Date</strong></td>
            <td width="10%"><div align="center"><strong>User Name</strong></div></td>
            <td width="24%"><strong>Email</strong></td>
            <td width="10%"><strong>Approval</strong></td>
            <td width="10%"> <strong>Banned</strong></td>
            <td width="10%"> <strong>User Level</strong></td>
            <td width="24%">&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <?php while ($rrows = mysql_fetch_array($rs_results)) {?>
          <tr>
            <td><input name="u[]" type="checkbox" value="<?php echo $rrows['id']; ?>" id="u[]"></td>
            <td><?php echo $rrows['date']; ?></td>
            <td> <div align="center"><?php echo $rrows['user_name'];?></div></td>
            <td><?php echo $rrows['user_email']; ?></td>
            <td> <span id="approve<?php echo $rrows['id']; ?>">
              <?php if(!$rrows['approved']) { echo "Pending"; } else {echo "Active"; }?>
              </span> </td>
            <td><span id="ban<?php echo $rrows['id']; ?>">
              <?php if(!$rrows['banned']) { echo "no"; } else {echo "yes"; }?>
              </span> </td>
              <td><?php echo $rrows['user_level'];?></td>
            <td><a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "approve", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#approve<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Approve</a>
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Ban</a>
              <a href="javascript:void(0);" onclick='$.get("do.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Unban</a>
              <a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Edit</a>
          </td>
          </tr>
          <tr>
            <td colspan="7">

			<div style="display:none;font: normal 11px arial; padding:10px; background: #e6f3f9" id="edit<?php echo $rrows['id']; ?>">

			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			Username: <input name="user_name<?php echo $rrows['id']; ?>" id="user_name<?php echo $rrows['id']; ?>" type="text" size="10" value="<?php echo $rrows['user_name']; ?>" >
			User Email:<input id="user_email<?php echo $rrows['id']; ?>" name="user_email<?php echo $rrows['id']; ?>" type="text" size="20" value="<?php echo $rrows['user_email']; ?>" >

            <br/>
            User Full Name: <input name="full_name<?php echo $rrows['id']; ?>" id="full_name<?php echo $rrows['id']; ?>" type="text" size="15" value="<?php echo $rrows['full_name']; ?>" >
            Level: <input id="user_level<?php echo $rrows['id']; ?>" name="user_level<?php echo $rrows['id']; ?>" type="text" size="5" value="<?php echo $rrows['user_level']; ?>" > 1->External,2->View Only,3->Road Service,5->Admin
			<br><br>New Password: <input id="pass<?php echo $rrows['id']; ?>" name="pass<?php echo $rrows['id']; ?>" type="text" size="20" value="" > (leave blank)
			<input name="doSave" type="button" id="doSave" value="Save"
			onclick='$.get("do.php",{ cmd: "edit", pass:$("input#pass<?php echo $rrows['id']; ?>").val(),user_level:$("input#user_level<?php echo $rrows['id']; ?>").val(),user_email:$("input#user_email<?php echo $rrows['id']; ?>").val(),user_name: $("input#user_name<?php echo $rrows['id']; ?>").val(),full_name: $("input#full_name<?php echo $rrows['id']; ?>").val(),ccountry: $("input#ccountry<?php echo $rrows['id']; ?>").val(),agent: $("input#agent<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val() } ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">close</a>

		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>

		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doApprove" type="submit" id="doApprove" value="Approve" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="doBan" type="submit" id="doBan" value="Ban" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="doUnban" type="submit" id="doUnban" value="Unban" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="doDelete" type="submit" id="doDelete" value="Delete" class="inline-flex items-center px-2 py-1 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="query_str" type="hidden" id="query_str" value="<?php echo $_SERVER['QUERY_STRING']; ?>">
				</br>
          <strong>Note:</strong> If you delete the user can register again, instead
          ban the user. </p>
        <p><strong>Edit Users:</strong> To change email, user name or password,
          you have to delete user first and create new one with same email and
          user name.</p>
      </form>

	  <?php } ?>
      &nbsp;</p>
	  <?php
	  if($_POST['doSubmit'] == 'Create')
{
$rs_dup = mysql_query("select count(*) as total from users where user_name='$post[user_name]' OR user_email='$post[user_email]'") or die(mysql_error());
list($dups) = mysql_fetch_row($rs_dup);

if($dups > 0) {
	die("The user name or email already exists in the system");
	}

if(!empty($_POST['pwd'])) {
  $pwd = $post['pwd'];
  $hash = PwdHash($post['pwd']);
 }
 else
 {
  $pwd = GenPwd();
  $hash = PwdHash($pwd);

 }

mysql_query("INSERT INTO users (`user_name`,`user_email`,`pwd`,`approved`,`date`,`user_level`,`ccountry`,`full_name`,`country`,`agent`)
			 VALUES ('$post[user_name]','$post[user_email]','$hash','1',now(),'$post[user_level]','$post[ccountry]','$post[name]','$post[ccountry]','$post[agent]')
			 ") or die(mysql_error());



$message =
"Thank you for registering with us. Here are your login details...\n
User Email: $post[user_email] \n
Passwd: $pwd \n

*****LOGIN LINK*****\n
http://$host$path/login.php

Thank You

Administrator
$host_upper
______________________________________________________
THIS IS AN AUTOMATED RESPONSE.
***DO NOT RESPOND TO THIS EMAIL****
";

if($_POST['send'] == '1') {

	mail($post['user_email'], "Login Details", $message,
    "From: \"Member Registration\" <auto-reply@$host>\r\n" .
     "X-Mailer: PHP/" . phpversion());
 }
echo "<div class=\"msg\">User created with password $pwd....done.</div>";
}

	  ?>

      <h2 class="font-semibold text-lg text-gray-800 leading-tight">Create New User</h2>
      <table class="text-base w-9/12 p-4">
        <tr>
          <td><form name="form1" method="post" action="admin.php">
						<div class="table w-full">
							<div class="table-row-group">
    						<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">User Name</div>
                	<div class="table-cell w-3/4 p-2"><input name="user_name" type="text" id="user_name" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Username"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Name</div>
									<div class="table-cell p-2"><input name="name" type="text" id="name" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type Full Name"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Email</div>
									<div class="table-cell p-2"><input name="user_email" type="text" id="user_email" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type Email Address"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">User Level</div>
									<div class="table-cell p-2">
										<select name="user_level" id="user_level" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm">
		                  <option value="1">External Contract</option>
		                  <option value="2">View Only</option>
		                  <option value="3">Road Service Personel</option>
		                  <option value="5">Admin</option>
		                </select>
									</div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Password</div>
									<div class="table-cell p-2"><input name="pwd" type="text" id="pwd" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Please Enter Password"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2"><input name="doSubmit" type="submit" id="doSubmit" value="Create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"></div>
									<div class="table-cell p-2"></div>
								</div>
							</div>
						</div>
            <input name="send" type="checkbox" id="send" value="1" unchecked hidden>
            </form>
            <p>**All created users will be approved by default.</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
	</div>
</div>
</div>
</body>
</html>
