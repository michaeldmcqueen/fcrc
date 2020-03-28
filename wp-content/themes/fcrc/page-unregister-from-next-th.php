<?php
/*
Template Name: unregister-from-next-tortoise-hare
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
	define('RACE_DATES_TABLE', 'race_dates');
	define('RACE_REGISTRATIONS_TABLE', 'race_registrations');
	define('MYSQL_DB_MEMBERSHIP', 'fcrc_membership');
	if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
		else { $first_name = $_POST['first_name']; } 
	if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
		else { $last_name = $_POST['last_name']; }
	if ($_GET['race_date'] != '') { $race_date = $_GET['race_date']; }
		else { $race_date = $_POST['race_date']; }
	$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
	$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");
	

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
	
	function confirmation_message() {
		global $current_series, $next_race, $distance, $first_name, $last_name;
		if ($next_race[location]) {
			echo '<p>'.$first_name.' '.$last_name.', you have successfully unregistered from the <strong>'.$next_race[location].' '.$next_race[kilometers].'k</strong> Tortoise & Hare race on <strong>'.$next_race['date'].'</strong>.</p>';
		}
	}
														
	function ask_for_name() {
		global $current_series, $next_race, $distance;
		echo '<p>Enter your name below to unregister from the <strong>'.$next_race[location].' '.$next_race[kilometers].'k</strong> Tortoise & Hare race on <strong>'.$next_race[date].'</strong>.</p>';
		echo '<form action="" method="post" />';
		echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
		echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
		echo '<input type="hidden" name="race_date" value="'.$next_race[date].'" />';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}	

	function check_if_already_registered($first_name, $last_name, $date) { 
		global $registration_info, $email;
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT * FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%" AND `race date` = "' . $date . '"';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$data[$num_data] = $row;		
			$num_data++;
		}
		$registration_info = $data[0];
		$email = $data[0]['email']; // for registered runners who were never FCRC members but entered an email address when asked
		return($num_data);
	}

	function get_member_data() {
		global $first_name, $last_name, $member_data;
		include '/home/fcrc/public_html/scripts/function-alias.php';
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
	
	function unregister($registrant) {
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}										
		$query = "DELETE FROM `" . RACE_REGISTRATIONS_TABLE . "` where `id` = " . $registrant['id'];
//		 echo $query .'<br />'; 
		mysqli_query($link,$query) or die('MySQL data entry failed.'); 
		mysqli_close($link);
	}

	function email_unregistration_confirmation() {
		global $first_name, $last_name, $next_race, $start_info, $member_data, $email;
		if ($member_data[0][expiration]!='') { // i.e., runner is a member in database
			if ($member_data[0][email]!='') { $email = $member_data[0][email]; }
			else echo '<p>Although you are an FCRC member, we have no email address for you on file. If you would like to be emailed registration confirmations and our weekly newsletter in the future, please <a href="mailto:membership@fortcollinsrunningclub.org">email us</a>.';
		}
		if ($email!='') {
			$to = $email; /* change to 'felix@fortcollinsrunningclub.org' for testing */
			$subject = 'You unregistered for the ' . $next_race[location] . ' ' . $next_race[kilometers] . 'k';
			$from = 'info@fortcollinsrunningclub.org';
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: '.$from."\r\n".
				'Reply-To: '.$from."\r\n" .
				'X-Mailer: PHP/' . phpversion();
			$body = '<html><body><p>'.$first_name . ' ' .$last_name. ', you successfully <strong>unregistered</strong> for the Fort Collins Running Club\'s '.$next_race[location].' '.$next_race[kilometers] . 'k <a href="https://fortcollinsrunningclub.org/tortoise-hare/">Tortoise & Hare</a> race on '.$next_race['date'].'.</p>Hope to see you at the <a href="https://fortcollinsrunningclub.org/register-for-next-th-race/">next race</a>!</p><p><img src="https://fortcollinsrunningclub.org/source_files/new-logo.png" /></p></body></html>';
			if (mail($to, $subject, $body, $headers)) {   
				echo '<p>Confirmation of your unregistration has been emailed to <em>'.$email.'</em>. If you want to update this address, <a href="mailto:membership@fortcollinsrunningclub.org">email us</a>.</p>';  
			} 
			else echo("<p>Email delivery of registration confirmation failed.</p>");  
		}
	}

	function is_registration_closed() {
		global $race_date;
		$registration_close = $race_date.' 8:00:00';
		$now = date("Y-m-d H:i:s");
		return ($now > $registration_close);
	}


	// BEGIN MAIN PROGRAM
	determine_next_race();
	if ($last_name=='' || $first_name=='' || $race_date=='') ask_for_name();
	else {
		if (!is_registration_closed()) {
			$registered = check_if_already_registered($first_name, $last_name, $race_date);
			if ($registered) {
				unregister($registration_info);
				confirmation_message();
				get_member_data();
				email_unregistration_confirmation();
			}
			else echo '<p>'.$first_name.' '.$last_name.' is not registered for the <strong>'.$next_race[location].' '.$next_race[kilometers].'k</strong> Tortoise & Hare race on <strong>'.$next_race[date].'</strong>. No unregistration is needed.</p>';
		}
		else echo '</p><p>The Tortoise & Hare race on '.$race_date.' already took place, so you cannot unregister from it.</p>';
		echo '<p>Click <a href="/register-for-next-th-race/">here</a> to register for the next Tortoise & Hare race.<p>';
	}
?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>