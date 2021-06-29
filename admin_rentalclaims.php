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
		mysql_query("update rental_request set active=0 where id=$id");
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
		mysql_query("update rental_request set active=1 where id=$id");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

$rs_all = mysql_query("select count(*) as total_all from rental_request") or die(mysql_error());
$rs_active = mysql_query("select count(*) as total_active from rental_request where active='1'") or die(mysql_error());
$rs_total_pending = mysql_query("select count(*) as tot from rental_request where active='0'");

list($total_pending) = mysql_fetch_row($rs_total_pending);
list($all) = mysql_fetch_row($rs_all);
list($active) = mysql_fetch_row($rs_active);


?>
<html>
<head>
<title>Rental/Claim Person Administration Page</title>
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
			 Rental/Claim Person Administration Page
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
			<a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin_rentalclaims.php">Admin Rental/Claim Person CP </a><br>
			<a class="underline text-blue-600 hover:text-blue-800 visited:text-purple-600" href="admin_rental_fleet.php">Admin Rental Fleet CP </a>
		<?php } ?>
	</div>
	</td>
  <td class="w-4/5 align-top px-6 py-6">
		<div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-6 py-6">
      <table class="text-base table-fixed w-full">
        <tr>
          <td class="w-1/3">Total: <?php echo $all;?></td>
          <td class="w-1/3">Active: <?php echo $active; ?></td>
        </tr>
      </table>
      <p><?php
	  if(!empty($msg)) {
	  echo $msg[0];
	  }
	  ?></p>
      <table class="text-base w-9/12 p-4" >
        <tr>
          <td><form name="form1" method="get" action="admin_rentalclaims.php">
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

	  $sql = "select * from rental_request where `name` LIKE '%$_REQUEST[q]%'";



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
			echo "<a href=\"admin_rentalclaims.php?$qstr&page=$page_no\">$page_no</a> ";
			$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin_rentalclaims.php" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr class="bg-gray-400">
            <th class="w-1/12 text-left"><strong>&nbsp;</strong></th>
            <th class="w-2/12 text-left"><strong>Name</strong></th>
            <th class="w-1/12 text-left"><strong>Claims Handler</strong></div></th>
            <th class="w-1/12 text-left"><strong>Survey Requestor</strong></th>
            <th class="w-1/12 text-left"><strong>Active</strong></th>
						<th class="w-6/12 text-left"><strong>&nbsp;</strong></th>
          </tr>
          <tr>
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
            <td><?php echo $rrows['name']; ?></td>
            <td><?php if ($rrows['isclaimsHandler']) { echo 'Yes';} else { echo 'No';} ?></td>
            <td><?php if ($rrows['isSurveyRequestor']) { echo 'Yes';} else { echo 'No';} ?></td>
            <td><?php if ($rrows['active']) { echo 'Yes';} else { echo 'No';} ?></td>
						<td>
							<a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Edit</a>
							<a href="javascript:void(0);" onclick='$.get("do_rentalclaims.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Ban</a>
              <a href="javascript:void(0);" onclick='$.get("do_rentalclaims.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Unban</a>

					</td>
        </tr>
        <tr>
          <td colspan="6">

			<div class="bg-gray-300 hidden p-3" id="edit<?php echo $rrows['id']; ?>">
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			<table class="table-fixed">
				<tr>
					<td class="w-1/6">Name:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="name<?php echo $rrows['id']; ?>" id="name<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['name']; ?>" ></td>
					<td class="w-1/6">Claims Handler:</td>
					<td class="w-2/6">
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" name="isclaimsHandler<?php echo $rrows['id']; ?>" id="isclaimsHandler<?php echo $rrows['id']; ?>"  >
							<option value="1" <?php if ($rrows['isclaimsHandler']==1) { echo 'selected="selected"'; }?>>Yes</option>
							<option value="0" <?php if ($rrows['isclaimsHandler']==0) { echo 'selected="selected"'; }?>>No</option>
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
        	<td>Survey Request:</td>
					<td>
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" name="isSurveyRequestor<?php echo $rrows['id']; ?>" id="isSurveyRequestor<?php echo $rrows['id']; ?>"  >
							<option value="1" <?php if ($rrows['isSurveyRequestor']==1) { echo 'selected="selected"'; }?>>Yes</option>
							<option value="0" <?php if ($rrows['isSurveyRequestor']==0) { echo 'selected="selected"'; }?>>No</option>
						</select>
					</td>
				</tr>
			</table>
				<br><br>
				<input name="doSave" type="button" id="doSave" value="Save"
			onclick='$.get("do_rentalclaims.php",{ cmd: "edit", name:$("input#name<?php echo $rrows['id']; ?>").val(),isclaimsHandler:$("select#isclaimsHandler<?php echo $rrows['id']; ?>").val(),isSurveyRequestor:$("select#isSurveyRequestor<?php echo $rrows['id']; ?>").val(),active:$("select#active<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val()} ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });setTimeout(function(){location.reload()},750);' class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
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

	mysql_query("INSERT INTO rental_request (`name`,`isclaimsHandler`,`active`,`isSurveyRequestor`)
				 VALUES ('$post[name]','$post[isclaimsHandler]',1,'$post[isSurveyRequestor]')
				 ") or die(mysql_error());

	echo "<div class=\"msg\">Rental/Claim Person Created.</div>";
}

	  ?>

      <h2 class="font-semibold text-lg text-gray-800 leading-tight">Create New Rental/Claim Person</h2>
      <table class="text-base w-9/12 p-4">
        <tr>
          <td><form name="form1" method="post" action="admin_rentalclaims.php">
						<div class="table w-full">
							<div class="table-row-group">
    						<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">Name</div>
                	<div class="table-cell w-3/4 p-2"><input name="name" type="text" id="name" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Username"></div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Is Claims Handler</div>
									<div class="table-cell p-2">
										<select name="isclaimsHandler" id="isclaimsHandler" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
									</div>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2">Is Survey Requestor</div>
									<div class="table-cell p-2">
										<select name="isSurveyRequestor" id="isSurveyRequestor" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm">
											<option value="0">No</option>
											<option value="1">Yes</option>
										</select>
								</div>
								<div class="table-row">
									<div class="table-cell text-right p-2"><input name="doSubmit" type="submit" id="doSubmit" value="Create" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150"></div>
									<div class="table-cell p-2"></div>
								</div>
							</div>
						</div>
            </form>
            <p>**All created will be active.</p></td>
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
