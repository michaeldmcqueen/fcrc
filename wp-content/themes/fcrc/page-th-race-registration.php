<?php
/*
Template Name: tortoise-hare-race-registration
*/
date_default_timezone_set('America/Denver');
get_header(); ?>
		<div id="primary">
			<div id="content" role="main">

				<?php // get_template_part( 'content', 'page' ); ?>
			
				<article class="page type-page status-publish hentry p-4 content-area md:w-4/5 mx-auto">
					<header class="entry-header">
						<h1 class="entry-title text-5xl"><?php the_title(); ?></h1>

					</header><!-- .entry-header -->
					<div class="entry-content">				
<?php
	// CONSTANTS & VARIABLES:

	define('FIRST_YEAR_IN_DB', '2011'); /* This variable is not needed since this script only uses data from $current_series and $current_series-1 (i.e., last two years) */
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'fcrc_wp');
	define('MYSQL_PASS', 'SgfmcjP077');
	define('MYSQL_DB', 'fcrc_tortoise_hare');
	define('PREDICTION_TABLE', 'manual_predictions');
	define('RACE_DATES_TABLE', 'race_dates');
	define('RACE_REGISTRATIONS_TABLE', 'race_registrations');
	define('MYSQL_DB_MEMBERSHIP', 'fcrc_membership');
	if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
		else { $first_name = $_POST['first_name']; } 
	if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
		else { $last_name = $_POST['last_name']; }
	if ($_GET['send_email'] == 'y') { $requested_email = $_POST['email']; } 
	$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
	$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");
	
	/* DETERMINE CURRENT SERIES */
		define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
		$today = date("m-d");
		if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
		else { $current_series = date("Y")-1; }
	/* end DETERMINE CURRENT SERIES */



	$zero_handicap_minutes = 16; // if Nick puts in zero handicap than 16:00 in handicap script, results will not agree
	$zero_handicap_seconds = 0;
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
	
	function introduction() {
		global $current_series, $next_race, $distance;
		if ($next_race[location]) {
			echo '<p>The next <a href="/tortoise-hare/">Tortoise & Hare</a> race is the <strong>'.$next_race[location].' '.$next_race[kilometers].'k</strong> (';
			$miles = $next_race[kilometers]*0.62137119;
			$distance = $miles; // for later calculations?
			echo round($miles,1).' miles) on <strong>'.$next_race[date].'</strong>. ';
		}
		else {
			echo '<p>The next T&H race has not been scheduled yet. Please come back to this page later.</p>';
		}
	}
														
	function ask_for_name() {
		echo '<p>Entry for each Tortoise and Hare closes at Noon the day before the race.</p>';
		echo '<p>Enter your name below. Then this registration tool will do the following:</p>';
		echo '<ol>';
		echo '<li>Check if you are already registered for this race. If so, it will display your start time and wave.</li>';
		echo '<li>Check if we have T&H race data for you from the last two seasons. If not, we will have you enter a predicted time.</li>';
		echo '</ol>';
		echo '<p>After the above checks are complete, you will be registered for the race and informed of your FCRC <a href="/membership-lookup/
" target="_blank">membership status</a>. If your membership is current on the day of the race, the race is <em>free</em>! If not, it will cost $10 (payable at race) unless you join the FCRC or renew your membership before then.</li>';
		echo '<form action="" method="post" />';
		echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
		echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}	

	function check_if_already_registered($first_name, $last_name, $date) { 
		global $registration_info;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT * FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%" AND `race date` = "' . $date . '"';
//		echo '$query is ' . $query . '<br />';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$data[$num_data] = $row;		
			$num_data++;
		}
		$registration_info = $data[0];
		return($num_data);
	}

	function get_data($series, $first_name, $last_name) { /* function for getting data from a particular year */
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT * FROM `' .$series. '` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%" ORDER BY `date` ASC';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$data[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
		return($data);
	}

	function get_data_last2years() { /* get all names from last 2 years, including redundant names */
		global $data_last2years, $current_series;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT `first name`,`last name` FROM `' .($current_series-1). '` ORDER BY `date` ASC';
		// echo '$query is ' . $query . '<br />';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$data_last2years[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		$query2 = 'SELECT `first name`,`last name` FROM `' .$current_series. '` ORDER BY `date` ASC';
		// echo '$query is ' . $query . '<br />';
		$all_data2 = mysqli_query($link,$query2);
		while ($row = mysqli_fetch_array($all_data2)) {
			$data_last2years[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data2);
		mysqli_close($link);
		// ELIMINATE SPACES BEFORE AND AFTER NAMES
		for ($j=0; $j<$num_data; $j++) {
			$data_last2years[$j]['first name'] = trim($data_last2years[$j]['first name']);
			$data_last2years[$j]['last name'] = trim($data_last2years[$j]['last name']);
		}
	}
	
	function round_to_30_seconds($time_in_seconds) {
		$multiples_of_30 = round($time_in_seconds / 30);
		$time_in_seconds = $multiples_of_30 * 30;
		return $time_in_seconds;
	}
	
	function check_for_manual_prediction($first_name, $last_name) {
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT * FROM `' .PREDICTION_TABLE. '` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%" ORDER BY `id` DESC';
		$num_manual_predictions=0;
		$all_manual_prediction_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_manual_prediction_data)) {
			$manual_predictions[$num_manual_predictions] = $row;		
			$num_manual_predictions++;
		}
		mysqli_free_result($all_manual_prediction_data);
		mysqli_close($link);
		$today = date("Y-m-d");
		if ($manual_predictions[0][date] > date('Y-m-d',(strtotime ( '-30 day' , strtotime ( $today) ) ))) // manual prediction has to be <30 days old
			return $manual_predictions[0];
		
	}
	
	/* This function calculates a new predicted time (IN SECONDS for requested $distance) for new runners who entered predicted times ($predicted_time) for a particular distance ($predicted_distance) into the PREDICTION_TABLE. */
	function adjusted_prediction_from_manual_prediction($predicted_time, $predicted_distance) {
		global $distance;
		$datum_seconds = (substr($predicted_time,0,2)*3600+substr($predicted_time,3,2)*60+substr($predicted_time,6,2))/($predicted_distance*0.621371192); // yields pace in seconds
		$datum_distance = $predicted_distance*0.621371192; //in miles
		$adj_factor = pow(1.05,((log($distance)-log($datum_distance))/log(2)));
		$adjusted_pace = $datum_seconds * $adj_factor; // yields adjusted pace in seconds
		$adjusted_time = $adjusted_pace * $distance;
		return ($adjusted_time);
	}
	
	/* function to convert a race time to a pace. */
	function pace($time) {  //$time should be in seconds
		global $distance;
		$pace = $time/$distance;
		return($pace);
	}
	
	function compare_pace($a, $b) { 
		return strnatcmp($b['pace'], $a['pace']); 
	}
	function compare_name($a, $b) { 
		return strnatcmp($a['first name'], $b['first name']); 
	}
	
	function get_predictions($first_name, $last_name) { /* gets predictions for people from last 2 years only */
		global $distance, $distance_in_km, $current_series;
		$num_previous_year_data = 0;
		$num_current_year_data = 0;
		$previous_year_data=get_data($current_series-1,$first_name, $last_name);
			if ($previous_year_data) {  // only do if person was in $current_series_data and hence there is data
				foreach ($previous_year_data as $previous_year_datum) {
					$num_previous_year_data++;
				}
			}
		$current_series_data=get_data($current_series,$first_name, $last_name);
			if ($current_series_data) {  // only do if person was in $current_series_data and hence there is data
				foreach ($current_series_data as $current_series_datum) {
					$num_current_year_data++;
				}
			}
		if ($previous_year_data) {
			$prediction_from_previous_year = prediction_from_data($previous_year_data);
		}
		if ($current_series_data) {
			$prediction_from_current_year = prediction_from_data($current_series_data);
		}
		if ($num_current_year_data==0) {
			$outputted_time = $prediction_from_previous_year['time'];
			$outputted_pace = $prediction_from_previous_year['pace'];
		}
		if ($num_current_year_data==1) {
			if ($num_previous_year_data==0) {
				$outputted_time = $prediction_from_current_year['time'];
				$outputted_pace = $prediction_from_current_year['pace'];
			}
			else {
				$outputted_time = ($prediction_from_current_year['time'] + $prediction_from_previous_year['time'])/2;
				$outputted_pace = ($prediction_from_current_year['pace'] + $prediction_from_previous_year['pace'])/2;
			}
		}
		if ($num_current_year_data>=2) {
			$outputted_time = $prediction_from_current_year['time'];
			$outputted_pace = $prediction_from_current_year['pace'];
		}
		$outputted_predictions['time']=$outputted_time;
		$outputted_predictions['pace']=$outputted_pace;
		return $outputted_predictions;
	}

	function prediction_from_data($data) { /* Calculates a predicted time for a specific $distance using $data (i.e., dataset from MySQL table; i.e., $data from specified year ) */
		global $distance;
		$num_pred_paces=0;
		if ($data) {
			foreach ($data as $datum) {
					$datum_seconds = (substr($datum['time'],0,2)*3600+substr($datum['time'],3,2)*60+substr($datum['time'],6,2))/($datum['distance']*0.621371192);
					$datum_distance = $datum['distance']*0.621371192; //in miles
					$adj_factor = pow(1.05,((log($distance)-log($datum_distance))/log(2)));
					$pred_paces[$num_pred_paces] = $datum_seconds * $adj_factor;
					$weight_of_races[$num_pred_paces] = weight_of_race($datum['date']);
					$num_pred_paces++;
				}
			for ($j=0; $j<$num_pred_paces; $j++) {
				$sum_of_pred_paces = $sum_of_pred_paces + $weight_of_races[$j]*$pred_paces[$j];
				$sum_of_weights = $sum_of_weights + $weight_of_races[$j];
			}
			
			$final_pred_pace = $sum_of_pred_paces / $sum_of_weights; /* this is overall WEIGHTED predicted pace */
			$final_pred_time = round($final_pred_pace * $distance);
			if ($num_pred_paces <= 2) {
				$prediction['pace'] = $final_pred_pace;
				$prediction['time'] = $final_pred_time;
			}
			else {
				/* Calculation for alternative predicted time below. It handles the situation where a runner has a large difference between the fastest predicted time and the slowest predicted time and most of the predicted times are clustered around the slowest predicted time.  */
				$min_pred_pace = min($pred_paces);
				$max_pred_pace = max($pred_paces);
				$alt_final_pred_pace = round($min_pred_pace+($max_pred_pace-$min_pred_pace)/3);		
				$alt_final_pred_time = round($distance*($min_pred_pace+($max_pred_pace-$min_pred_pace)/3));
				$prediction['pace'] = min($final_pred_pace, $alt_final_pred_pace);
				$prediction['time'] = min($final_pred_time, $alt_final_pred_time);
			}
		}
		return ($prediction);
	}


	function weight_of_race($date) {
		$month = substr($date,5,2);
		$day = substr($date,8,2);
		switch ($month) {
			case '01': $weight=4; break;
			case '02': $weight=5; break;
			case '03': $weight=6; break;
			case '04': $weight=7; break;
			case '05': $weight=1; break; /* won't have T&H race in this month, but if so, count once */
			case '06': $weight=1; break; /* won't have T&H race in this month, but if so, count once */
			case '07': $weight=1; break; /* won't have T&H race in this month, but if so, count once */
			case '08': $weight=1; break; /* won't have T&H race in this month, but if so, count once */
			case '09': $weight=1; break; /* won't have T&H race in this month, but if so, count once */
			case '10': $weight=1; break;
			case '11': $weight=2; break;
			case '12': $weight=3; break;
		}
		if ($day > 15) $weight++; /* for instances where race was at end of month; e.g., March 30 race instead of early April */
		return ($weight);
	}
	
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
		if ($m=='60') {$h++; $m=0;}
		if ($h<0 || $m<0 || $s<0) {$time='-';}
		if (abs($h)<10) {$time=$time.'0'.abs($h).':';}
		else {$time=$time.abs($h).':';}
		if (abs($m)<10) {$time=$time.'0'.abs($m);}
		else {$time=$time.abs($m);}
		if (abs($s)<10) {$time=$time.':0'.abs($s);}
		else {$time=$time.':'.abs($s);}
		return $time;
	}

	function start_time_minute($secs) {
		$h=intval($secs/3600);
		$m=intval(($secs-$h*3600)/60);
		$s=round($secs-$h*3600-$m*60);
		if ($s=='60') {$m++; $s=0;}
		if ($m=='60') {$h++; $m=0;}
		return abs($m);
	}

	function start_time_second($secs) {
		$h=intval($secs/3600);
		$m=intval(($secs-$h*3600)/60);
		$s=round($secs-$h*3600-$m*60);
		if ($s=='60') {$m++; $s=0;}
		if ($m=='60') {$h++; $m=0;}
		return abs($s);
	}

	
	function get_staggers($time) {
		global $distance, $zero_handicap_minutes, $zero_handicap_seconds;
		$zero_handicap_pace = $zero_handicap_minutes*60 + $zero_handicap_seconds;
		$zero_handicap_finish_time = $zero_handicap_pace * $distance;
		$handicap = $zero_handicap_finish_time - $time; // in seconds
		$staggers['handicap']=round_to_30_seconds($handicap);
		$staggers['start_time']=28800+$staggers['handicap']; // 28000=8:00:00 in seconds
		return ($staggers);
	}

	
	function no_data_for_runner() {
		global $first_name, $last_name, $start_info, $member_data, $next_race;
		echo '<p>'.$first_name.' '.$last_name.', we do not have recent race data for you. This is probably because you have not done a T&H race in the last two seasons and have not entered a predicted time.</p>';
		echo '<p>If that\'s the case, please <a href="/enter-th-predicted-time/?first_name='.$first_name.'&last_name='.$last_name.'
">enter a predicted time</a>. Your registration will continue afterward.</p>';
		echo 'Or perhaps your name is spelled differently in our database. This could be the case if you have a nickname, have a \' or space in your name, or have changed names recently.  If so, hit the back button on your web browser and try an alternate spelling (e.g., omitting spaces or apostrophes).</p>';
		echo '<p>Otherwise, please <a href="mailto:felix@fortcollinsrunningclub.org">email Felix Wong</a> (FCRC webmaster) and he will help you.</p>';
	}


	
	function ask_for_email() {
		global $first_name, $last_name;
		echo '<p>So that we can send you confirmation of this registration and contact you with important pre-race announcements, <strong>please enter your email address</strong>:</p>';
		echo '<form action="/register-for-next-th-race/?first_name='.$first_name.'&last_name='.$last_name.'&send_email=y" method="post" />';
		echo '<p>Email: <input type="text" name="email" value="" size="50" maxlength="50" /></p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}



	
	function output_start_info($start_info) {
		global $first_name, $last_name, $member_data, $next_race, $requested_email;
		include '/home/fcrc/public_html/scripts/function-alias.php';
		echo '<p>Your start time and wave is:</p>';
		echo '<h2>'.$start_info['start time'].' a.m.</h2>';
		$start_hour = substr(format_hhmmss($start_info['start_time']),1,1);
		echo '<h2>Wave '.$start_info['start wave'].'</h2>';
		get_member_data();
		$today_yyyymmdd = date("Y-m-d");
		if (!name_is_in_db()) name_not_found_error();
		elseif ($member_data[0]['expiration']<$today_yyyymmdd) echo '<p><strong>Your membership expired on '.$member_data[0]['expiration'].'.</strong> T&H races are $10/race for non-members, payable at the race. Or <a href="/join-the-fcrc/">renew your membership</a> today!</p>'; 
		echo '<p>Your predicted time is '.$start_info['predicted time']. ' ('.substr($start_info['predicted pace'],3,5).'/mile pace). ';
		if ($start_info['prediction type'] == 'calculated') echo 'This was calculated from <a href="/race-calculator/?first_name='.$first_name.'&last_name='.$last_name.'">your T&H race results</a> from this season and/or the last one.</p>';
		elseif ($start_info['prediction type'] == 'manual') echo 'This is based off a predicted time that you entered earlier.</p>';
		echo '<p><em>Assumption: The 16:00/mile runners start at 8:00:00 A.M. ' . /* Complete list of registered runners can be accessed <a href="/handicap-times-for-runners-in-database-for-next-race/">here</a>. */ '</em></p>'; 
		if ($member_data[0]['expiration']>$today_yyyymmdd) echo '<p>Your membership expires on '.$member_data[0]['expiration'].'.</strong> If it is coming up soon, you can <a href="/join-the-fcrc/">renew</a> today.</p>';
		echo '<p>If you have questions about any of the above, please email <a href="mailto:felix@fortcollinsrunningclub.org">Felix Wong</a> (FCRC Webmaster). If you cannot make it to the race, you can <a href="https://fortcollinsrunningclub.org/unregister-from-th-race/?first_name='.$first_name.'&last_name='.$last_name.'&race_date='.$next_race['date'].'">unregister</a> (optional).</p>';
		if (!name_is_in_db() && $requested_email=='') ask_for_email();
	}


	function get_member_data() {
		global $first_name, $last_name, $member_data;
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
		//echo '$query is ' . $query . '<br />';
		$num_member_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$member_data[$num_member_data] = $row;		
			$num_member_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
	}
	
	function name_is_in_db() {
		global $member_data;
		return ($member_data != NULL);
	}
	
	function name_not_found_error() {
		global $member_data, $first_name, $last_name;
		echo '<p><strong>You do not seem to be an FCRC member</strong>. T&H races are $10/race for non-members, payable at the race. You can check your <a href="/membership-lookup/">membership status</a> or <a href="/join-the-fcrc/">join the FCRC</a> today.</p>';
	}
	

	function write_registration_to_db($start_info) {
		global $first_name, $last_name, $next_race;
		$today = date("Y-m-d");
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}										
		$query = "INSERT INTO `" . RACE_REGISTRATIONS_TABLE . "` (`id`, `last name`, `first name`, `location`, `kilometers`, `registration date`, `race date`, `predicted time`, `predicted pace`, `prediction type`, `start time`, `start wave`) VALUES (NULL, '"
			.mysqli_real_escape_string($link,$last_name)."', '"
			.mysqli_real_escape_string($link,$first_name)."', '"
			.$next_race['location']."', '"
			.$next_race['kilometers']."', '"
			.$today."', '"
			.$next_race['date']."', '"
			.$start_info['predicted time']."', '"
			.$start_info['predicted pace']."', '"
			.$start_info['prediction type']."', '"
			.$start_info['start time']."', '"
			.$start_info['start wave']."')";
//		 echo $query .'<br />'; 
		mysqli_query($link,$query) or die('MySQL data entry failed.'); 
		mysqli_close($link);
	}

	function wave_number($start_time_in_sec) {
		$start_time_hhmmss = format_hhmmss($start_time_in_sec);
		$start_hour = substr($start_time_hhmmss,1,1);
		if ($start_hour == '7') $value_to_add_to_wave = 700;
		elseif ($start_hour == '9') $value_to_add_to_wave = 60;
		else $value_to_add_to_wave = 0;
		$start_time_minutes = start_time_minute($start_time_in_sec)+$value_to_add_to_wave;
		if (start_time_second($start_time_in_sec)=='30') return ($start_time_minutes .'B');
		else return ($start_time_minutes);
	}

	function is_registration_closed() {
		global $next_race, $registration_close, $registration_open, $now;
		$registration_close = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $next_race[date]) ) )).' 12:00:00'; // close noon day before race
		$registration_open = date('Y-m-d',(strtotime ( '-21 day' , strtotime ( $next_race[date]) ) )).' 12:00:00'; // does not open until 21 days before next race
		$now = date("Y-m-d H:i:s");
		return ($now > $registration_close || $now < $registration_open);
	}

	
	function add_requested_email_to_db() {
		global $first_name, $last_name, $requested_email, $next_race;
			// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}	
		$query = "UPDATE `" . RACE_REGISTRATIONS_TABLE . "` set `email` = '".$requested_email."' WHERE `first name`='".$first_name."' AND `last name`='".$last_name."' AND `race date` = '".$next_race['date']."'";	
//		echo $query .'<br />'; 
		mysqli_query($link,$query) or die('MySQL data entry failed.'); 
		mysqli_close($link);
	}
	
	
	
	function email_registration_confirmation() {
		global $first_name, $last_name, $next_race, $start_info, $member_data, $requested_email;
		if ($member_data[0][expiration]!='') { // i.e., runner is a member in database
			if ($member_data[0][email]!='') {
				$email = $member_data[0][email];
			}
			else echo '<p>Although you are an FCRC member, we have no email address for you on file. If you would like to be emailed registration confirmations and our weekly newsletter in the future, please <a href="mailto:membership@fortcollinsrunningclub.org">email us</a>.';
		}
		elseif ($requested_email!='') $email = $requested_email; // had requested email for non-member
		if ($email!='') {
			$to = $email; /* change to 'felix@fortcollinsrunningclub.org' for testing */
			$subject = 'Registration confirmation for ' . $next_race[location] . ' ' . $next_race[kilometers] . 'k';
			$from = 'info@fortcollinsrunningclub.org';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$from."\r\n".
				'Reply-To: '.$from."\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$body = '<html><body><p>'.$first_name . ' ' .$last_name. ', you are now registered for the Fort Collins Running Club\'s '.$next_race[location].' '.$next_race[kilometers] . 'k <a href="https://fortcollinsrunningclub.org/tortoise-hare/">Tortoise & Hare</a> race on '.$next_race['date'].'!</p><p>Your start time is <strong>'.$start_info['start time'].'</strong> and your start wave is <strong>Wave '.$start_info['start wave'].'</strong>. We are predicting that you will run the race in '.$start_info['predicted time'].' ('.substr($start_info['predicted pace'],3,5).'/mile).</p><p>Please show up about half an hour before your start time to pick up your race bib.</p><p>If you cannot make it to the race, you can <a href="https://fortcollinsrunningclub.org/unregister-from-th-race/?first_name='.$first_name.'&last_name='.$last_name.'&race_date='.$next_race['date'].'">unregister</a> (optional).</p>Have a great race!</p><p><img src="https://fortcollinsrunningclub.org/source_files/new-logo.png" /></p></body></html>';
			if (mail($to, $subject, $body, $headers)) {   
				echo '<p>Confirmation of your registration has been emailed to <em>'.$email.'</em>. If you want to update this address, <a href="mailto:membership@fortcollinsrunningclub.org">email us</a>.</p>';  
			} 
			else echo("<p>Email delivery of registration confirmation failed.</p>");  
			
		}
	}

	// BEGIN MAIN PROGRAM
	determine_next_race();
	if ($last_name=='') introduction();
	if (!is_registration_closed()) {
		if ($last_name=='') {
				echo 'Register for it here!</p>';
				if ($next_race) ask_for_name(); 
		}
		else {
			if (check_if_already_registered($first_name, $last_name, $next_race[date])) {
				// check_if_already_registered function fills global variable $registration_info with data if exists in db
				if ($requested_email=='') {
					echo '<p><strong>'.$registration_info['first name'].' '.$registration_info['last name'].'</strong>, you are already registered for the upcoming <strong>'.$registration_info['location'].' '.$registration_info['kilometers'].'k</strong> ('.$next_race['date'].'). You registered on '.$registration_info['registration date'].'. </p>';
					output_start_info($registration_info);
				}
				else {
					add_requested_email_to_db();
					echo '<p><strong>'.$registration_info['first name'].' '.$registration_info['last name'].'</strong>, you are registered for the upcoming <strong>'.$registration_info['location'].' '.$registration_info['kilometers'].'k</strong> ('.$next_race['date'].').';
					output_start_info($registration_info);
					$start_info=$registration_info;
					email_registration_confirmation();
				}

			}
			else { 
				$predictions = get_predictions($first_name, $last_name); // checks database for data and calculates predicted time
				//print_r($predictions);
				if ($predictions[time]) {
					$staggers = get_staggers($predictions[time]);
					$start_info['start time'] = format_hhmmss($staggers[start_time]);
					$start_info['predicted time'] = format_hhmmss($predictions['time']);
					$start_info['predicted pace'] = format_hhmmss($predictions['pace']);
					$start_info['start wave'] = wave_number($staggers[start_time]);
					$start_info['prediction type'] = 'calculated';
					write_registration_to_db($start_info);
					echo '<p>'.$first_name.' '.$last_name.', congratulations! You are now registered for the upcoming <strong>'.$next_race['location'].' '.$next_race['kilometers'].'k</strong> on '.$next_race['date'].'.</p>';	
					output_start_info($start_info);
					email_registration_confirmation();
				}
				elseif (check_for_manual_prediction($first_name,$last_name)) {
					$manual_prediction = check_for_manual_prediction($first_name,$last_name);
					$predicted_time_adjusted_for_distance = adjusted_prediction_from_manual_prediction($manual_prediction[predicted],$manual_prediction[distance]);
					$staggers = get_staggers($predicted_time_adjusted_for_distance);
					$start_info['start time'] = format_hhmmss($staggers[start_time]);
					$start_info['predicted time'] = format_hhmmss($predicted_time_adjusted_for_distance);
					$start_info['predicted pace'] = format_hhmmss(pace($predicted_time_adjusted_for_distance));
					$start_info['start wave'] = wave_number($staggers[start_time]);
					$start_info['prediction type'] = 'manual';
					write_registration_to_db($start_info);
					echo '<p>'.$first_name.' '.$last_name.', congratulations! You are now registered for the <strong>'.$next_race['location'].' '.$next_race['kilometers'].'k</strong> ('.$next_race['date'].').</p>';	
					output_start_info($start_info);
					email_registration_confirmation();
				}
				else no_data_for_runner();
			}
		}
	}
	else {
		echo '</p><p>Unfortunately, ';
		// $registration_close, $registration_open, $now below are global variables from is_registration_closed() function
		if ($now > $registration_close) echo '<strong>registration is closed</strong>. ';
		else echo '<strong>registration is not open yet</strong>. '; // this is when $now < $registration_open
		echo 'It opens three weeks before a race and closes at noon the day before the race. There is no race-day registration. This is because we have so many runners at different computer-generated start times that race-day registration would make things too chaotic for our volunteers.
</p><p>We are sorry for the inconvenience. Hopefully you can make it to our next <a href="/tortoise-hare/">Tortoise & Hare</a> race!</p>';
	}
?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>