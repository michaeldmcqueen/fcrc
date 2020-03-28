<?php
/*
Template Name: input-th-results
*/
?>
				
<?php
	// CONSTANTS & VARIABLES:
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'fcrc_wp');
	define('MYSQL_PASS', 'SgfmcjP077');
	define('MYSQL_DB', 'fcrc_tortoise_hare');
	define('RACE_DATES_TABLE', 'race_dates');
	define('RACE_REGISTRATIONS_TABLE', 'race_registrations');
	define('PREDICTION_DB', 'manual_predictions');
	define('PASSWORD', 'T&H*0525');
	define('NEW_FIELDS', 5);
	define('PEOPLE_PER_PAGE', 115);

	$page = $_POST['page'];
	if (!$page) {$page=1;}
	$date = $_POST['date'];
	$location = $_POST['location'];
	$password = $_POST['password'];
	$distance_in_km = $_POST['distance'];
	$distance = $distance_in_km *0.621371192;
	$late_minutes = $_POST['late_minutes'];
	$late_seconds = $_POST['late_seconds'];
	$delay = $late_minutes*60 + $late_seconds;
	$zero_handicap_minutes = $_POST['zero_handicap_minutes'];
	$zero_handicap_seconds = $_POST['zero_handicap_seconds'];
	$num_potential_entries = $_POST['num_potential_entries'];

	/* DETERMINE CURRENT SERIES */
		define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
		$today = date("m-d");
		if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
		else { $current_series = date("Y")-1; }
	/* end DETERMINE CURRENT SERIES */

	function determine_last_race() {
		global $last_race, $distance, $distance_in_km;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$today = date("Y-m-d");
		$query = 'SELECT * FROM `' .RACE_DATES_TABLE. '` ORDER BY `date` DESC';
		$all_scheduled_races = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_scheduled_races)) {
			$scheduled_races[$num_scheduled_races] = $row;		
			$num_scheduled_races++;
		}
		mysqli_free_result($all_scheduled_races);
		mysqli_close($link);
		foreach ($scheduled_races as $scheduled_race) {
			if ($today >= $scheduled_race[date]) {
				$last_race = $scheduled_race;
				break;
			}
		}
		$distance_in_km = $last_race['kilometers'];
		$distance = $distance_in_km *0.621371192;
	}	
	
	function introduction() {
		echo '<p>This tool is for entering new T&H results. ';
		echo 'To edit existing T&H results, <a href="http://fortcollinsrunningclub.org:2082/3rdparty/phpMyAdmin/index.php?db=fcrc_tortoise_hare&token=4da4304b10340f67ec276b31bb1ac76d#PMAURL:db=fcrc_tortoise_hare&table=2012&target=sql.php&token=2cb9e17fc89c0a96901cb5c95d913386">click here</a>.';
	}
	
	function password_error() {
		echo '<p>Incorrect password.  Please press the BACK button on your browser and try again.</p>';
		echo '<p>Please note that this tool is meant for the race director only and not for general FCRC members.</p>';
	}
	
	function entry_error() {
		echo '<p>Missing parameters.  Please press the BACK button on your browser and try again.</p>';
	}
	function get_parameters() {
		global $last_race;
		echo '<p>Please enter the following information:</p>';
		echo '<form action="" method="post" />';
		echo '<p>Date: <input type="text" name="date" value="'.$last_race['date'].'" size="10" maxlength="10" /> (YYYY-MM-DD)</p>';
		echo '<p>Location: <input type="text" name="location" value="'.$last_race['location'].'" size="30" maxlength="30" /> (e.g., Spring Park, Rolland Moore Park, Lee Martinez Park)</p>';
		echo '<p>Distance: <input type="text" name="distance" value="'.$last_race['kilometers'].'" size="3" maxlength="3" /> kilometers</p>';
		echo '<p>Pace for zero handicap: <input type="text" name="zero_handicap_minutes" value="16" size="2" maxlength="2" />:<input type="text" name="zero_handicap_seconds" value="00" size="2" maxlength="2" />/mile (i.e., pace of runner who was supposed to start at 8:00 A.M.)</p>';
		echo '<p>The race started late by <input type="text" name="late_minutes" value="0" size="2" maxlength="2" /> minutes <input type="text" name="late_seconds" value="0" size="2" maxlength="2" /> seconds (will be added to staggers)</p>';
		echo '<p>Administrative password: <input type="password" name="password" value="" size="15" maxlength="15" /> (hint: all lower case)</p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}

	function get_data() { 
		global $location, $distance_in_km, $date, $sort_by, $handicaps;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT * FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `location` LIKE "' . $location . '" AND `kilometers` = ' . $distance_in_km . ' AND `race date` = "'.$date.'" ORDER BY `first name` ASC';
		// echo '$query is ' . $query . '<br />';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$handicaps[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
		echo '<p>There were <strong>'.$num_data.'</strong> registered runners.</p>';
		// print_r($handicaps);
	}

	/* function to convert a race time to a pace. */
	function convert_time_in_seconds_to_pace($time) {  //$time should be in seconds
		global $distance;
		$pace = $time/$distance;
		return($pace);
	}
	
	// ----------- TIME CONVERSION FUNCTIONS -----------
	function format_mmss($secs) {
		$m=intval($secs/60);
		$s=round($secs-$m*60);
		if ($s=='60') {$m++; $s=0;}
		if ($m<0 || $s<0) {$time='-';}
		if (abs($m)<10) {$time=$time.'0'.abs($m);}
		else {$time=$time.abs($m);}
		if (abs($s)<10) {$time=$time.':0'.abs($s);}
		else {$time=$time.':'.abs($s);}
		return $time;
	}
	
	function format_hhmmss($secs) {
		$h=intval($secs/3600);
		$m=intval(($secs-$h*3600)/60);
		$s=round($secs-$h*3600-$m*60);
		if ($s=='60') {$m++; $s=0;}
		if ($m=='60') {$h++; $m=60;}
		if ($h<0 || $m<0 || $s<0) {$time='-';}
		if (abs($h)<10) {$time=$time.'0'.abs($h).':';}
		else {$time=$time.abs($h).':';}
		if (abs($m)<10) {$time=$time.'0'.abs($m);}
		else {$time=$time.abs($m);}
		if (abs($s)<10) {$time=$time.':0'.abs($s);}
		else {$time=$time.':'.abs($s);}
		return $time;
	}

	function ms_to_hhmmss($ms) {
		$hms = '0:'.$ms;
		$seconds = strtotime("January 1, 1970 $hms");
		return(format_hhmmss($seconds));		
	}
	
	function hms_to_hhmmss($hms){
		if (substr($hms,0,1)=='-') {$seconds = -1*strtotime('January 1, 1970 '.substr($hms,1));}
		else {$seconds = strtotime("January 1, 1970 $hms");}
		return(format_hhmmss($seconds));
	}

	function hhmmss_to_secs($time) {
		if (substr($time,0,1)=='-') {$secs = -1*(substr($time,1,2)*3600+substr($time,4,2)*60+substr($time,7,2));}
		else {$secs = substr($time,0,2)*3600+substr($time,3,2)*60+substr($time,6,2);}
		return ($secs);
	}
	// ----------- END OF TIME CONVERSTION FUNCTIONS -----------
	
	
	function output_table() { // Produces form for entering new results
		global $handicaps, $distance_in_km, $location, $date, $delay, $page, $zero_handicap_minutes, $zero_handicap_seconds;
		$num_handicaps=0;
		foreach ($handicaps as $handicap) {
			$num_handicaps++;
		}
		$num_pages=ceil($num_handicaps/PEOPLE_PER_PAGE);

		echo '<form name="Archives" action="." method="post"><p>This form is limited to '.PEOPLE_PER_PAGE.' entries per page.  You are looking at page '.$page.' of '.$num_pages.' of all the names in the database. ';
		echo 'Go to page: <select name="page" size="1">';
		for ($p=1; $p<=$num_pages; $p++) {
			if ($p!=$page) {echo '<option value="'.$p.'">'.$p.'</option>';}
		}
		echo '</select>';
		echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
		echo '<input type="hidden" name="distance" value="'.$distance_in_km.'" />';
		echo '<input type="hidden" name="location" value="'.$location.'" />';
		echo '<input type="hidden" name="date" value="'.$date.'" />';
		echo '<input type="hidden" name="zero_handicap_minutes" value="'.$zero_handicap_minutes.'" />';
		echo '<input type="hidden" name="zero_handicap_seconds" value="'.$zero_handicap_seconds.'" />';		
		echo '<input type="submit" value="Go" /></form></p>';
		
		
		echo '<form action="." method="post" />';
		echo '<table border="0"><tr><th>Name</th><th>Predicted Time</th><th>Handicap</th><th>Clock Time</th><th>Place</th></tr>';
		$first_person_to_list=($page-1)*PEOPLE_PER_PAGE+1;
		if ($num_handicaps > ($first_person_to_list+PEOPLE_PER_PAGE)) {$last_person_to_list=$first_person_to_list+PEOPLE_PER_PAGE;}
		else {$last_person_to_list=$num_handicaps;}
		for ($h=$first_person_to_list; $h<$last_person_to_list; $h++) {
			echo '<tr><td>';
			if ($handicaps[$h]['prediction type']=='manual') {echo '<i>';}
			echo $handicaps[$h]['first name'].' '.$handicaps[$h]['last name'];
			if ($handicaps[$h]['prediction type']=='manual') {echo '</i>';}
			echo '</td>';
			echo '<input type="hidden" name="data['.$h.'][first_name]" value="'.$handicaps[$h]['first name'].'" />';
			echo '<input type="hidden" name="data['.$h.'][last_name]" value="'.$handicaps[$h]['last name'].'" />';
			echo '<td align="center"><input type="text" name="data['.$h.'][predicted_time]" value="'.$handicaps[$h]['predicted time'].'" size="8" maxlength="8" /></td>';
			$handicap_in_sec = (substr($handicaps[$h]['start time'],0,2)-8)*3600 + substr($handicaps[$h]['start time'],3,2)*60 + substr($handicaps[$h]['start time'],6,2);
			echo '<td align="center"><input type="text" name="data['.$h.'][handicap]" value="'.format_hhmmss($handicap_in_sec+$delay).'" size="9" maxlength="9" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][clock_time]" value="" size="8" maxlength="8" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][place]" value="" size="3" maxlength="3" /></td>';
			echo '<input type="hidden" name="data['.$h.'][first_race]" value="0" />';
			if ($handicaps[$h]['prediction type']=='manual') {
				echo '<input type="hidden" name="data['.$h.'][points]" value="5" />';
				echo '<input type="hidden" name="data['.$h.'][first_race]" value="1" />';			
			}
		}
		for ($h=$last_person_to_list; $h<$last_person_to_list+NEW_FIELDS; $h++) { //fields for new runners
			echo '<tr><td align="left"><input type="text" name="data['.$h.'][first_name]" value="" size="15" maxlength="15" />';
			echo ' <input type="text" name="data['.$h.'][last_name]" value="" size="15" maxlength="15" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][predicted_time]" value="" size="8" maxlength="8" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][handicap]" value="" size="9" maxlength="9" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][clock_time]" value="" size="8" maxlength="8" /></td>';
			echo '<td align="center"><input type="text" name="data['.$h.'][place]" value="" size="3" maxlength="3" /></td>';
			echo '<input type="hidden" name="data['.$h.'][points]" value="5" />';
			echo '<input type="hidden" name="data['.$h.'][first_race]" value="1" />';
		}
		echo '<tr><th>Name</th><th>Predicted Time</th><th>Handicap</th><th>Clock Time</th><th>Place</th></tr>';
		echo '</table>';
		echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
		echo '<input type="hidden" name="distance" value="'.$distance_in_km.'" />';
		echo '<input type="hidden" name="location" value="'.$location.'" />';
		echo '<input type="hidden" name="date" value="'.$date.'" />';
		echo '<input type="hidden" name="page" value="'.$page.'" />';
		echo '<input type="hidden" name="num_potential_entries" value="'.($last_person_to_list-$first_person_to_list+NEW_ENTRIES).'" />';
		echo '<input type="submit" name="Submit" value=" Submit " />';		
	}

	function add_data_to_db() {
		global $page, $distance_in_km, $location, $date, $num_potential_entries, $current_series;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$first_person_to_list=($page-1)*PEOPLE_PER_PAGE+1;
		$last_person_to_list=$first_person_to_list+$num_potential_entries;
		for ($i=$first_person_to_list; $i<$last_person_to_list+NEW_FIELDS; $i++) {
			if ($_POST['data'][$i]['clock_time'] != '') {
				$data[$i]['first_name'] = trim($_POST['data'][$i]['first_name']);
				$data[$i]['last_name'] = trim($_POST['data'][$i]['last_name']);
				$data[$i]['predicted_time'] = hms_to_hhmmss($_POST['data'][$i]['predicted_time']);
				$data[$i]['handicap'] = hms_to_hhmmss($_POST['data'][$i]['handicap']);
				$data[$i]['clock_time'] = $_POST['data'][$i]['clock_time'];
				if (strrpos($data[$i]['clock_time'],':')==1 || strrpos($data[$i]['clock_time'],':')==2) {
					$data[$i]['clock_time'] = ms_to_hhmmss($data[$i]['clock_time']);
				} 
				else $data[$i]['clock_time'] = hms_to_hhmmss($data[$i]['clock_time']);
				$data[$i]['time'] = format_hhmmss(hhmmss_to_secs($data[$i]['clock_time'])-hhmmss_to_secs($data[$i]['handicap']));
				$data[$i]['place'] = $_POST['data'][$i]['place'];
				$data[$i]['points'] = $_POST['data'][$i]['points'];
				$data[$i]['pace'] = pace($data[$i]['time']);
				$data[$i]['first_race'] = $_POST['data'][$i]['first_race'];
				$query = "INSERT INTO `" . $current_series . "` ( `date` , `location` , `distance` , `place`, `first name`, `last name`, `predicted`, `time`, `pace`, `points`, `first_race`) VALUES ('";
				$query = $query.$date."', '".$location."', '".$distance_in_km."', '".$data[$i]['place']."', '".$data[$i]['first_name']."', '".$data[$i]['last_name']."', '".$data[$i]['predicted_time']."', '".$data[$i]['time']."', '".$data[$i]['pace']."', '".$data[$i]['points']."', '".$data[$i]['first_race']."')";
				echo $query .'<br />';
				mysqli_query($link,$query) or die('MySQL data entry failed.'); 
			}
		}
		mysqli_close($link);
		// Confirmation page
		award_points();
		echo '<p><a href="http://fortcollinsrunningclub.org:2082/3rdparty/phpMyAdmin/index.php?db=fcrc_tortoise_hare&token=4da4304b10340f67ec276b31bb1ac76d#PMAURL:db=fcrc_tortoise_hare&table=2012&target=sql.php&token=2cb9e17fc89c0a96901cb5c95d913386" target="_blank">Edit data</a></p>';
		echo '<p>You can check the results you just entered by visiting the <a href="http://fortcollinsrunningclub.org/results/" target="_blank">Results</a> page.</p>';
	}

	function award_points() {
		global $distance_in_km, $location, $date, $current_series;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT `first name`,`last name`,`place`,`first_race` FROM `' .$current_series. '` WHERE `date` = "'.$date.'" AND `location` = "'.$location.'" AND `distance` = "'.$distance_in_km.'" AND `first_race` = "0" ORDER BY `place` ASC';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$race_data[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		$available_points = 25;
		if (is_array($race_data)) {
			foreach ($race_data as $datum) {
				$query = 'UPDATE `'.$current_series.'` SET `points`="'.$available_points.'" WHERE `first name`="'.$datum['first name'].'" AND `last name`="'.$datum['last name'].'" AND `date` = "'.$date.'" AND `location` = "'.$location.'" AND `distance` = "'.$distance_in_km.'"';
				mysqli_query($link,$query) or die('MySQL data entry failed.'); 
				if ($available_points>5) {$available_points = $available_points-1;}
			}
		}
		mysqli_close($link);
	}
	

	function pace($time) {
		global $distance;
		$seconds = strtotime("January 1, 1970 $time");
//		echo '$time is '.$time.' and $seconds is '.$seconds.' and $distance is '.$distance;
		$pace = $seconds/$distance;
		return(format_mmss($pace));
	}
	
	
	
	
	// BEGIN MAIN PROGRAM
	if ($password == '') {
		determine_last_race();
		introduction();
		get_parameters();
	}
	elseif ($password != PASSWORD) {
		password_error();
	}
	elseif (($date=='' || $location=='' || $distance=='') && $num_potential_entries=='') {
		entry_error();
	}
	elseif ($num_potential_entries!='') {
		add_data_to_db();
	}
	else {
		echo '<p>You are inputting data for the <strong>'. $location . ' ' .$distance_in_km.' km</strong> that took place on <strong>'.$date.'</strong>.</p>';
		if ($late_minutes!=0 || $late_seconds!=0) {echo '<p>The race started late by <strong>0:'.$late_minutes.':'.$late_seconds.'</strong>.  The handicaps have been automatically adjusted accordingly.';}
		else {echo'</p>The race started on time (no adjustments to handicaps were needed).</p>';}
		echo '<p>Only rows where a Clock Time has been entered will be added to the database.  (Clock Time is the time on the Race Director\'s stopwatch like 0:30:20; NOT the time of day!)  You may add new runners at the bottom of this form.</p>';
		echo '<p>Times must be entered in H:M:S format (e.g., 0:21:3 or 00:21:03).</p>';
		echo '<p>Only participants who participated in a T&H race in the last 2 years, and people who entered a manually entered predicted time online (<i>denoted in italics</i>), are listed.</p>';
		get_data();
		output_table();

	}
?>
