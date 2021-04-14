<?php
include 'dbc.php';
page_protect();

if(!checkAdmin()) {
header("Location: login.php");
exit();
}

$page_limit = 10;
error_reporting(0);

//error_reporting(E_ALL);
//ini_set('display_errors', '1');

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
		mysql_query("update adjuster set active=0 where id=$id");
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
		mysql_query("update adjuster set active=1 where id=$id");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

$rs_all = mysql_query("select count(*) as total_all from adjuster") or die(mysql_error());
$rs_active = mysql_query("select count(*) as total_active from adjuster where active='1'") or die(mysql_error());
$rs_total_pending = mysql_query("select count(*) as tot from adjuster where active='0'");

list($total_pending) = mysql_fetch_row($rs_total_pending);
list($all) = mysql_fetch_row($rs_all);
list($active) = mysql_fetch_row($rs_active);


?>
<html>
<head>
<title>Adjusters Administration Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="styles.css" rel="stylesheet" type="text/css">
<link href="css/tailwind.css" rel="stylesheet">
<script language="JavaScript" type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

</head>

<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 p-4">
<header class="bg-white shadow">
	<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
		<h2 class="font-semibold text-2xl text-gray-800 leading-tight">
			 Adjusters Administration Page
	 </h2>
 </div>
</header>
<div class="py-12">
<table class="table-fixed">
  <tr>
    <td class="w-1/5 align-top px-6 py-6">
		<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
		<?php if (isset($_SESSION['user_id'])) {?>
		  <h2 class="font-semibold text-lg text-gray-800 leading-tight">My Account</h2>
		  <a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="./">Home</a><br>
		  <a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="myaccount.php">My Account</a><br>
	    <a class="underline text-base text-blue-600 hover:text-blue-800 visited:text-purple-600" href="logout.php">Logout </a><br>
		<?php }
		if (checkAdmin()) {
		/*******************************END**************************/
		?>
      <a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin.php">Admin CP </a><br>
			<a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin_adjusters.php">Admin Adjuster CP </a><br>
			<a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin_attendees.php">Admin Attendee CP </a><br>
			<a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin_rentalclaims.php">Admin Rental/Claim Person CP </a>
		<?php } ?>
	</div>
	</td>
  <td class="w-4/5 align-top px-6 py-6">
		<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-6 py-6">
      <table class="text-base table-fixed w-full">
        <tr>
          <td class="w-1/3">Total Adjusters: <?php echo $all;?></td>
          <td class="w-1/3">Active Adjusters: <?php echo $active; ?></td>
        </tr>
      </table>
      <p><?php
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
      <table class="text-base w-9/12 p-4" >
        <tr>
          <td><form name="form1" method="get" action="admin_adjusters.php">
              <p align="center">Search
                <input name="q" id="q" value="<?php echo $_GET['q'];?>" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm">
                <br>
                [Type Name] </p>
              <p align="center">
                <input name="doSearch" type="submit" id="doSearch2" value="Search" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
              </p>
              </form></td>
        </tr>
      </table>
      <p>
        <?php if ($get['doSearch'] == 'Search') {

	  $sql = "select * from adjuster where `name` LIKE '%$_REQUEST[q]%' or `email` LIKE '%$_REQUEST[q]%' ";



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
			echo "<a href=\"admin_adjusters.php?$qstr&page=$page_no\">$page_no</a> ";
			$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin_adjusters.php" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr class="bg-gray-400">
            <th class="w-1/12 text-left"><strong>&nbsp;</strong></th>
            <th class="w-2/12 text-left"><strong>Name</strong></th>
            <th class="w-3/12 text-left"><strong>Approve</strong></th>
            <th class="w-3/12 text-left"><strong>Approve Non Preferred</strong></th>
            <th class="w-1/12 text-left"><strong>Active</strong></th>
						<th class="w-2/12 text-left"><strong>&nbsp;</strong></th>

          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
						<td>&nbsp;</td>
          </tr>
          <?php
					$bg=0;
					while ($rrows = mysql_fetch_array($rs_results)) {
						if($bg==1){
							$bgc='class="bg-gray-100"';
							$bg=0;
						}
						else{
							$bgc='';
							$bg=1;
						}
					?>
          <tr <?php echo $bgc; ?>>
            <td class="align-top"><input name="u[]" type="checkbox" value="<?php echo $rrows['id']; ?>" id="u[]"></td>
            <td class="align-top"><?php echo $rrows['name']; ?></td>
            <td class="align-top">
							<?php
								$a = explode(',',$rrows['approve']);
								foreach ($a as $approver){
									if($rrows['approve'] !==''){
										echo '<div class="flex flex-row py-1"><div class="w-3/5">'.getUserFNameID($approver).'</div><div class="w-2/5"><a href="javascript:void(0);" onclick='."'".'$.get("do_adjusters.php",{ cmd: "rmapp", id: "'.$rrows['id'].'", approver: "'.$approver.'"} ,function(data){ $("#rmapp'.$rrows['id'].$approver.'").html(data); });setTimeout(function(){location.reload()},750);'."'".' class="inline-flex items-center px-1 py-1 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Remove</a></div></div>';
									}
								}
							?>
						</td>
            <td class="align-top">
							<?php
								$a = explode(',',$rrows['approve_non_preferred']);
								foreach ($a as $approver){
									if($rrows['approve_non_preferred'] !==''){
										echo '<div class="flex flex-row py-1"><div class="w-3/5">'.getUserFNameID($approver).'</div><div class="w-2/5"><a href="javascript:void(0);" onclick='."'".'$.get("do_adjusters.php",{ cmd: "rmappn", id: "'.$rrows['id'].'", approver: "'.$approver.'"} ,function(data){ $("#rmappn'.$rrows['id'].$approver.'").html(data); });setTimeout(function(){location.reload()},750);'."'".' class="inline-flex items-center px-1 py-1 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Remove</a></div></div>';
									}
								}
							?>
						</td>
            <td class="align-top"><?php if ($rrows['active']) { echo 'Yes';} else { echo 'No';} ?></td>
						<td class="align-top">
							<a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Edit</a>
							<a href="javascript:void(0);" onclick='$.get("do_adjusters.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Ban</a>
              <a href="javascript:void(0);" onclick='$.get("do_adjusters.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Unban</a>

					</td>
        </tr>
          <tr>
            <td colspan="6">

			<div class="bg-gray-300 p-3 hidden" id="edit<?php echo $rrows['id']; ?>">
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			<table class="table-auto">
				<tr>
					<td>Name:</td>
					<td><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="name<?php echo $rrows['id']; ?>" id="name<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['name']; ?>" ></td>
					<td>Add approver for approved garages:</td>
					<td>
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" id="approve<?php echo $rrows['id']; ?>" name="approve<?php echo $rrows['id']; ?>">
							<option value=""></option>
							<?php
								$sqlt = "select * from users where approved=1 AND banned=0 order by full_name ASC";
								$rst = mysql_query($sqlt);
								while($rowt = mysql_fetch_array($rst)){
									echo '<option value="'.$rowt['id'].'" >'.$rowt['full_name'].'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Email:</td>
					<td><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="email<?php echo $rrows['id']; ?>" id="email<?php echo $rrows['id']; ?>" type="email" value="<?php echo $rrows['email']; ?>" ></td>
        	<td>Add approver for non-approved garages:</td>
					<td>
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" id="approve_non_preferred<?php echo $rrows['id']; ?>" name="approve_non_preferred<?php echo $rrows['id']; ?>">
							<option value=""></option>
							<?php
								$sqlt = "select * from users where approved=1 AND banned=0 order by full_name ASC";
								$rst = mysql_query($sqlt);
								while($rowt = mysql_fetch_array($rst)){
									echo '<option value="'.$rowt['id'].'" >'.$rowt['full_name'].'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Active:</td>
					<td>
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" name="active<?php echo $rrows['id']; ?>" id="active<?php echo $rrows['id']; ?>"  >

							<option value="1" <?php if ($rrows['active']==1) { echo 'selected="selected"'; }?>>Yes</option>
							<option value="0" <?php if ($rrows['active']==0) { echo 'selected="selected"'; }?>>No</option>
						</select>
					</td>
        	<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
				<br><br>
				<input name="doSave" type="button" id="doSave" value="Save"
			onclick='$.get("do_adjusters.php",{ cmd: "edit", name:$("input#name<?php echo $rrows['id']; ?>").val(),email:$("input#email<?php echo $rrows['id']; ?>").val(),approve:$("select#approve<?php echo $rrows['id']; ?>").val(),approve_non_preferred:$("select#approve_non_preferred<?php echo $rrows['id']; ?>").val(),active:$("select#active<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val()} ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });setTimeout(function(){location.reload()},750);' class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">close</a>

		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>

		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doBan" type="submit" id="doBan" value="Ban" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="doUnban" type="submit" id="doUnban" value="Unban" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
        </br>
      </form>

	  <?php } ?>
      &nbsp;</p>
	  <?php
	  if($_POST['doSubmit'] == 'Create')
{

	mysql_query("INSERT INTO adjuster (`name`,`email`,`active`,`address`,`phone`,`mobile`,`approve`,`approve_non_preferred`)
				 VALUES ('$post[name]','$post[email]',1,'$post[address]','','','','')
				 ") or die(mysql_error());
	echo "<div class=\"msg\">Adjuster Created.</div>";
}

	  ?>

      <h2 class="font-semibold text-lg text-gray-800 leading-tight">Create New Adjuster</h2>
      <table class="text-base w-9/12 p-4">
        <tr>
          <td><form name="form1" method="post" action="admin_adjusters.php">
						<div class="table w-full">
							<div class="table-row-group">
    						<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">Name</div>
                	<div class="table-cell w-3/4 p-2"><input name="name" type="text" id="name" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Username"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Email</div>
									<div class="table-cell p-2"><input name="email" type="email" id="email" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type Full Name"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Address</div>
									<div class="table-cell p-2"><input name="address" type="text" id="address" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type Full Name"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2"><input name="doSubmit" type="submit" id="doSubmit" value="Create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"></div>
									<div class="table-cell p-2"></div>
								</div>
							</div>
						</div>
            </form>
            <p>**All created adjusters will be active.</p></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p></td>
    <td width="12%">&nbsp;</td>
	</div>
  </tr>
</table>
</div>
</div>
</body>
</html>
