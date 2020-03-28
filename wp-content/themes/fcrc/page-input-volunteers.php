<?php
/*
Template Name: input-volunteers
*/
?>
				
<?php
	// CONSTANTS & VARIABLES:
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'fcrc_wp');
	define('MYSQL_PASS', 'SgfmcjP077');
	define('MYSQL_DB', 'fcrc_membership');
	define('MYSQL_TABLE', 'volunteers');
	define('PASSWORD', 'T&H*0525');
	define('NEW_FIELDS', 20);

	/* DETERMINE CURRENT SERIES */
		define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
		$today = date("m-d");
		if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
		else { $current_series = date("Y")-1; }
	/* end DETERMINE CURRENT SERIES */

	$date = $_POST['date'];
	$event = $_POST['event'];
	$password = $_POST['password'];
	$distance_in_km = $_POST['distance'];
	$distance = $distance_in_km *0.621371192;


	function introduction() {
		echo '<p>This tool is for entering FCRC volunteers. ';
		echo 'To edit the volunteer database, <a href="https://secure192.servconfig.com:2083/cpsess4455722988/3rdparty/phpMyAdmin/index.php#PMAURL-1:sql.php?db=fcrc_membership&table=volunteers&server=1&target=&token=86fb63af747ff8972dc88b7be63f4243">click here</a>.';
	}
	
	function password_error() {
		echo '<p>Incorrect password.  Please press the BACK button on your browser and try again.</p>';
		echo '<p>Please note that this tool is meant for the race director only and not for general FCRC members.</p>';
	}
	
	function entry_error() {
		echo '<p>Missing parameters.  Please press the BACK button on your browser and try again.</p>';
	}
	function get_parameters() {
		echo '<p>Please enter the following information:</p>';
		echo '<form action="" method="post" />';
		echo '<p>Date: <input type="text" name="date" value="" size="10" maxlength="10" /> (YYYY-MM-DD)</p>';
		echo '<p>Event/location: <input type="text" name="event" value="" size="30" maxlength="30" /> (e.g., Rolland Moore Park, Lee Martinez Park; Member Breakfast)</p>';
		echo '<p>Distance: <input type="text" name="distance" value="" size="3" maxlength="3" /> kilometers (if for race like T&H.  Leave blank if for other event.)</p>';
		echo '<p>Administrative password: <input type="password" name="password" value="" size="15" maxlength="15" /> (hint: all lower case)</p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}

	
	
	function sort_by_first_name($a, $b) { 
		$retval = strnatcmp($a['first name'], $b['first name']);
		if(!$retval) return strnatcmp($a['last name'], $b['last name']);
		return $retval;		
	}
	
	
	function output_table() { // Produces form for entering new results
		global $distance_in_km, $event, $date;
		echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
		echo '<input type="hidden" name="distance" value="'.$distance_in_km.'" />';
		echo '<input type="hidden" name="event" value="'.$event.'" />';
		echo '<input type="hidden" name="date" value="'.$date.'" />';
		
		
		echo '<form action="." method="post" />';
		echo '<table border="0"><tr><th align="left">First name</th><th align="left">Last name</th></tr>';
		for ($h=0; $h<NEW_FIELDS; $h++) { //fields for new runners
			echo '<tr><td align="left"><input type="text" name="data['.$h.'][first_name]" value="" size="15" maxlength="15" />';
			echo '</td><td><input type="text" name="data['.$h.'][last_name]" value="" size="15" maxlength="15" /></td></tr>';
		}
		echo '</table>'; 
		echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
		echo '<input type="hidden" name="distance" value="'.$distance_in_km.'" />';
		echo '<input type="hidden" name="event" value="'.$event.'" />';
		echo '<input type="hidden" name="date" value="'.$date.'" />';
		echo '<input type="submit" name="Submit" value=" Submit " />';		
		echo '</form>';
	}

	function data_to_add_to_db() {
		global $distance_in_km, $event, $date, $num_potential_entries;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$num_potential_entries=0;
		for ($i=0; $i<NEW_FIELDS; $i++) {
			if ($_POST['data'][$i]['first_name'] != '') {
				$data[$i]['first_name'] = trim($_POST['data'][$i]['first_name']);
				$data[$i]['last_name'] = trim($_POST['data'][$i]['last_name']);
				$query = "INSERT  INTO `" . MYSQL_TABLE . "` ( `date` , `first name`, `last name`, `event` , `distance` ) VALUES ('";
				$query = $query.$date."', '".$data[$i]['first_name']."', '".$data[$i]['last_name']."', '".$event."', '".$distance_in_km."')";
				echo $query .'<br />';
				mysqli_query($link,$query) or die('MySQL data entry failed.'); 
				$num_potential_entries++;
			}
		}
		mysqli_close($link);
		return ($num_potential_entries>0);
	}

	function confirmation_message() {
		echo '<p><a href="https://secure192.servconfig.com:2083/cpsess4455722988/3rdparty/phpMyAdmin/index.php#PMAURL-1:sql.php?db=fcrc_membership&table=volunteers&server=1&target=&token=86fb63af747ff8972dc88b7be63f4243" target="_blank">Edit data</a></p>';
		echo '<p>If you were entering T&H volunteers, you can check if they are listed on the <a href="http://fortcollinsrunningclub.org/results/" target="_blank">Results</a> page. Or you can continue entering more volunteers below.</p>';
	}

	
	// BEGIN MAIN PROGRAM
	if ($password == '') {
		introduction();
		get_parameters();
	}
	elseif ($password != PASSWORD) {
		password_error();
	}
	elseif ($date=='' || $event=='') {
		entry_error();
	}
	else {
		if (data_to_add_to_db()) confirmation_message();
		echo '<p>Input volunteers for the <strong>'. $event . ' ' .$distance_in_km.' km</strong> that took place on <strong>'.$date.'</strong>.</p>';
			output_table();
	}
?>
