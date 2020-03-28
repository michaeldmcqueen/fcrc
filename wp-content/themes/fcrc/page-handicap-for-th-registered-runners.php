<?php
/*
Template Name: handicap-for-th-registered-runners
*/
?>
				
<?php
date_default_timezone_set('America/Denver');
	
	// CONSTANTS & VARIABLES:
	define('FIRST_YEAR_IN_DB', '2011'); /* This variable is not needed since this script only uses data from $current_series and $current_series-1 (i.e., last two years) */
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'fcrc_wp');
	define('MYSQL_PASS', 'SgfmcjP077');
	define('MYSQL_DB', 'fcrc_tortoise_hare');
	define('RACE_DATES_TABLE', 'race_dates');
	define('RACE_REGISTRATIONS_TABLE', 'race_registrations');
	define('MYSQL_DB_MEMBERSHIP', 'fcrc_membership');
	include '/home/fcrc/public_html/scripts/function-alias.php';
	
	
	$date = $_GET['date']; // normally not called, but can use to manually get registration for past events
	$location = $_GET['location']; // normally not called, but can use to manually get registration for past events
	$distance_in_km = $_GET['distance'];
	$distance = $distance_in_km *0.621371192;
	$sort_by = $_POST['sort_by'];

	function determine_next_race() {
		global $next_race, $distance, $distance_in_km;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$today = date("Y-m-d");
		$query = 'SELECT * FROM `' .RACE_DATES_TABLE. '` ORDER BY `date` ASC';
		$all_scheduled_races = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_scheduled_races)) {
			$scheduled_races[$num_scheduled_races] = $row;		
			$num_scheduled_races++;
		}
		mysqli_free_result($all_scheduled_races);
		mysqli_close($link);
		foreach ($scheduled_races as $scheduled_race) {
			if ($today <= $scheduled_race[date]) {
				$next_race = $scheduled_race;
				break;
			}
		}
		$distance_in_km = $next_race['kilometers'];
		$distance = $distance_in_km *0.621371192;
	}	
	
	function introduction($location, $kilometers, $date) {
		global $current_series;
		echo '<h1>Handicaps for T&H Registered Runners</h1>';
		echo 'The next Tortoise & Hare race is the <strong>'.$location.' '.$kilometers.'k</strong> on <strong>'.$date.'</strong>. <br />This tool outputs the handicaps for runners who registered for it.</p>';
	}
	
	function get_parameters() {
		global $location, $distance_in_km, $date;
		echo '<form action="?location='.$location.'&distance='.$distance_in_km.'&date='.$date.'" method="post" />';
		echo '<p><em>Sort by name</em> to create a printout for Number Pickup Volunteers.</p><p><em>Sort by pace</em> to create a printout for Start Line Announcers.</p>';
		echo '<p>Sort by: <input type="radio" name="sort_by" value="name" />name <input type="radio" name="sort_by" value="pace" checked="yes">pace</p>';
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
		if ($sort_by=='pace') {
			$query = 'SELECT * FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `location` LIKE "' . $location . '" AND `kilometers` = ' . $distance_in_km . ' AND `race date` = "'.$date.'" ORDER BY `start time` ASC, `predicted time` DESC, `first name` ASC';
		}
		else { // sorted by last name
			$query = 'SELECT * FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `location` LIKE "' . $location . '" AND `kilometers` = ' . $distance_in_km . ' AND `race date` = "'.$date.'" ORDER BY `last name` ASC';
		}
		// echo '$query is ' . $query . '<br />';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$handicaps[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
		echo '<p>There are <strong>'.$num_data.'</strong> registered runners.</p>';
		// print_r($handicaps);
	}


	function check_membership($first_name, $last_name, $provided_email) {
		$today_yyyymmdd = date("Y-m-d");
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB_MEMBERSHIP);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$alias = alias($first_name);
		if (!$alias) {
			$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"'; 
		}
		else {
			$query = 'SELECT * FROM `members` WHERE (`first name` LIKE "%' . $first_name . '%" OR `first name` LIKE "%'. $alias . '%") AND `last name` LIKE "%' . $last_name . '%"';
		}
		$num_member_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$member_data[$num_member_data] = $row;		
			$num_member_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
		if ($member_data == NULL) {
			echo 'non-member';
			if ($provided_email != '') echo '<br />'.$provided_email;
		}
		elseif ($member_data[0]['expiration']<$today_yyyymmdd) echo 'exp. '.$member_data[0]['expiration'].'<br />'.$member_data[0]['email'];
		else echo '-';
	}
	
	function output_table() {
		global $handicaps, $sort_by;
		if ($sort_by=='pace') echo '<table><tr><th style="text-align: left">Name</th><th width="120">Start Time</th><th>Wave</th><th>Predicted Time</th><th>Pace</th><th>Derivation</th><th>Membership Notes</th>';
		else echo '<table><tr><th style="text-align: left">Last Name</th><th style="text-align: left">First Name</th><th width="120">Start Time</th><th>Wave</th><th>Predicted Time</th><th>Pace</th><th>Derivation</th><th>Membership Notes</th>';
		$wave = $handicaps[0]['start_time'];
		foreach ($handicaps as $datum) {
			echo '<tr>';
			/* Name column(s) */
			if ($sort_by=='pace') {
				if ($datum['start time'] != $wave) echo '<td style="border-top: 1px solid black;">';
				else echo '<td>';
				echo $datum['first name'].' '.$datum['last name'];
				echo '</td>';
			}
			else { // must have been sorted by name
				echo '<td style="border-top: 1px solid black;">'.$datum['last name'].'</td><td style="border-top: 1px solid black;">'.$datum['first name'].'</td>';
			}
			/* Start time column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			echo $datum['start time'];
			echo '</td>';
			/* Wave # column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			echo $datum['start wave'];
			echo '</td>';
			/* Predicted Time column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			echo ($datum['predicted time']).'</td>';
			echo '</td>';
			/* Predicted Pace column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			echo substr($datum['predicted pace'],3,5);
			echo '</td>';
			/* Prediction Derivation column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			if ($datum['prediction type']=='manual') echo 'manual';
			else echo '-';
			echo '</td>';
			/* Membership Notes column */
			if ($datum['start time'] != $wave || $sort_by=='name') echo '<td style="text-align: center; border-top: 1px solid black;">';
			else echo '<td style="text-align: center;">';
			check_membership($datum['first name'],$datum['last name'],$datum['email']);
			echo '</td></tr>';
			$wave = $datum['start time'];
		}
		echo '</table>';
	}
	
	
	// BEGIN MAIN PROGRAM
	if ($sort_by =='') { // means that user had not filled out form yet
		if ($location == '') { // means that user did not do a manual request for info by typing in variables in the URL
			determine_next_race();
			$location = $next_race['location'];
			$distance_in_km = $next_race['kilometers'];
			$date = $next_race['date'];
		}
		introduction($location,$distance_in_km,$date);
		get_parameters();
	}
	else {
		echo '<h1>Registered runners for '.$location.' '.$distance_in_km.'k</h1>';
		echo '<p><i>Assumption: The 16:00/mile runners start at 8:00:00 A.M. "Manual" derivation means that time was calculated from runner-inputed predicted time.</i></p>';
		get_data();
		output_table();

	}
?>
