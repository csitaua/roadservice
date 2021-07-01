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

if($post['doBan'] == 'Disable') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("update rental_vehicle set active=0 where id=$id");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

if($_POST['doUnban'] == 'Enable') {

if(!empty($_POST['u'])) {
	foreach ($_POST['u'] as $uid) {
		$id = filter($uid);
		mysql_query("update rental_vehicle set active=1 where id=$id");
	}
 }
 $ret = $_SERVER['PHP_SELF'] . '?'.$_POST['query_str'];;

 header("Location: $ret");
 exit();
}

$rs_all = mysql_query("select count(*) as total_all from rental_vehicle") or die(mysql_error());
$rs_active = mysql_query("select count(*) as total_active from rental_vehicle where active='1'") or die(mysql_error());
$rs_total_pending = mysql_query("select count(*) as tot from rental_vehicle where active='0'");

list($total_pending) = mysql_fetch_row($rs_total_pending);
list($all) = mysql_fetch_row($rs_all);
list($active) = mysql_fetch_row($rs_active);


?>
<html>
<head>
<title>Rental Vehicle Administration Page</title>

<?php echo adminMenu(); ?>

<header class="bg-white shadow">
	<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
		<h2 class="font-semibold text-2xl text-gray-800 leading-tight">
			 Rental Vehicle Administration Page
	 </h2>
 </div>
</header>
<div class="py-12">
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
          <td><form name="form1" method="get" action="admin_rental_fleet.php">
              <p align="center">Search
                <input name="q" id="q" value="<?php echo $_GET['q'];?>" class="border py-1 px-1 text-grey-darkest w-1/2 rounded-md shadow-sm">
                <br>
                [Type License Plate] </p>
              <p align="center">
                <input name="doSearch" type="submit" id="doSearch2" value="Search" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
              </p>
              </form></td>
        </tr>
      </table>
      <p>
        <?php if ($get['doSearch'] == 'Search') {

	  $sql = "select * from rental_vehicle where `licenseplate` LIKE '%$_REQUEST[q]%'";



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
			echo "<a href=\"admin_rental_fleet.php?$qstr&page=$page_no\">$page_no</a> ";
			$i++;
		}
		echo "</div>";
		}  ?>
		</p>
		<form name "searchform" action="admin_rental_fleet.php" method="post">
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
          <tr class="bg-gray-400">
            <th class="w-1/12 text-left"><strong>&nbsp;</strong></th>
            <th class="w-1/12 text-left"><strong>License Plate</strong></th>
            <th class="w-1/12 text-left"><strong>Make</strong></div></th>
            <th class="w-2/12 text-left"><strong>Model</strong></th>
            <th class="w-1/12 text-left"><strong>Year</strong></th>
						<th class="w-1/12 text-left"><strong>Rental Price</strong></th>
						<th class="w-1/12 text-left"><strong>Active</strong></th>
						<th class="w-4/12 text-left"><strong>&nbsp;</strong></th>
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
            <td><?php echo $rrows['licenseplate']; ?></td>
            <td><?php echo $rrows['make']; ?></td>
						<td><?php echo $rrows['model']; ?></td>
						<td><?php echo $rrows['year']; ?></td>
						<td><?php echo $rrows['rental']; ?></td>
						<td><?php if ($rrows['active']) { echo 'Yes';} else { echo 'No';} ?></td>
						<td>
							<a href="javascript:void(0);" onclick='$("#edit<?php echo $rrows['id'];?>").show("slow");' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Edit</a>
							<a href="javascript:void(0);" onclick='$.get("do_rental_fleet.php",{ cmd: "ban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });setTimeout(function(){location.reload()},750);' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Disable</a>
              <a href="javascript:void(0);" onclick='$.get("do_rental_fleet.php",{ cmd: "unban", id: "<?php echo $rrows['id']; ?>" } ,function(data){ $("#ban<?php echo $rrows['id']; ?>").html(data); });setTimeout(function(){location.reload()},750);' class="inline-flex items-center px-0.5 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">Enable</a>

					</td>
        </tr>
        <tr>
          <td colspan="6">

			<div class="bg-gray-300 hidden p-3" id="edit<?php echo $rrows['id']; ?>">
			<input type="hidden" name="id<?php echo $rrows['id']; ?>" id="id<?php echo $rrows['id']; ?>" value="<?php echo $rrows['id']; ?>">
			<table class="table-fixed">
				<tr>
					<td class="w-1/6">License Plate:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="licenseplate<?php echo $rrows['id']; ?>" id="licenseplate<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['licenseplate']; ?>" ></td>
					<td class="w-1/6">Make:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="make<?php echo $rrows['id']; ?>" id="make<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['make']; ?>" ></td>
				</tr>
				<tr>
					<td class="w-1/6">Model:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="model<?php echo $rrows['id']; ?>" id="model<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['model']; ?>" ></td>
					<td class="w-1/6">Year:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="year<?php echo $rrows['id']; ?>" id="year<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['year']; ?>" ></td>
				</tr>
				<tr>
					<td class="w-1/6">Fee:</td>
					<td class="w-2/6"><input class="border p-1 text-grey-darkest rounded-md shadow-sm" name="rental<?php echo $rrows['id']; ?>" id="rental<?php echo $rrows['id']; ?>" type="text" value="<?php echo $rrows['rental']; ?>" ></td>
					<td>Active:</td>
					<td>
						<select class="border p-1 text-grey-darkest rounded-md shadow-sm" name="active<?php echo $rrows['id']; ?>" id="active<?php echo $rrows['id']; ?>"  >
							<option value="1" <?php if ($rrows['active']==1) { echo 'selected="selected"'; }?>>Yes</option>
							<option value="0" <?php if ($rrows['active']==0) { echo 'selected="selected"'; }?>>No</option>
						</select>
					</td>
				</tr>
			</table>
				<br><br>
				<input name="doSave" type="button" id="doSave" value="Save"

			onclick='$.get("do_rental_fleet.php",{ cmd: "edit", licenseplate:$("input#licenseplate<?php echo $rrows['id']; ?>").val(),make:$("input#make<?php echo $rrows['id']; ?>").val(),model:$("input#model<?php echo $rrows['id']; ?>").val(),year:$("input#year<?php echo $rrows['id']; ?>").val(),rental:$("input#rental<?php echo $rrows['id']; ?>").val(),active:$("select#active<?php echo $rrows['id']; ?>").val(),id: $("input#id<?php echo $rrows['id']; ?>").val()} ,function(data){ $("#msg<?php echo $rrows['id']; ?>").html(data); });setTimeout(function(){location.reload()},750);' class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">

			<a  onclick='$("#edit<?php echo $rrows['id'];?>").hide();' href="javascript:void(0);" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">close</a>

		  <div style="color:red" id="msg<?php echo $rrows['id']; ?>" name="msg<?php echo $rrows['id']; ?>"></div>
		  </div>

		  </td>
          </tr>
          <?php } ?>
        </table>
	    <p><br>
          <input name="doBan" type="submit" id="doBan" value="Disable" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
          <input name="doUnban" type="submit" id="doUnban" value="Enable" class="inline-flex items-center px-2 py-1 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
        </br>
      </form>

	  <?php } ?>
      &nbsp;</p>
	  <?php
	  if($_POST['doSubmit'] == 'Create')
{

	mysql_query("INSERT INTO rental_vehicle (`licenseplate`,`make`,`model`,`year`,`rental`,`active`,`available`,`sort`,`currentRentalId`)
				 VALUES ('$post[licenseplate]','$post[make]','$post[model]','$post[year]','$post[rental]',1,1,100,0)
				 ") or die(mysql_error());

	echo "<div class=\"msg\">Rental Vehicle Created.</div>";
}

	  ?>

      <h2 class="font-semibold text-lg text-gray-800 leading-tight">Create New Rental Vehicle</h2>
      <table class="text-base w-9/12 p-4">
        <tr>
          <td><form name="form1" method="post" action="admin_rental_fleet.php">
						<div class="table w-full">
							<div class="table-row-group">
    						<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">License Plate</div>
                	<div class="table-cell w-3/4 p-2"><input name="licenseplate" type="text" id="licenseplate" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the License Plate"></div>
								</div>
								<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">Make</div>
                	<div class="table-cell w-3/4 p-2"><input name="make" type="text" id="make" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Make"></div>
								</div>
								<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">Model</div>
                	<div class="table-cell w-3/4 p-2"><input name="model" type="text" id="model" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Model"></div>
								</div>
								<div class="table-row p-4">
              		<div class="table-cell w-1/4 text-right p-2">Year</div>
                	<div class="table-cell w-3/4 p-2"><input name="year" type="text" id="year" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Year"></div>
								</div>
								<div class="table-row p-4">
									<div class="table-cell w-1/4 text-right p-2">Fee</div>
									<div class="table-cell w-3/4 p-2"><input name="rental" type="text" id="rental" class="border py-1 px-1 text-grey-darkest w-4/5 rounded-md shadow-sm" placeholder="Type the Fee"></div>
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
	</div>
</div>
</div>
</body>
</html>
