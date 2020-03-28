<?php
/*
Template Name: Email addresses of registered T&H runners
*/


get_header();
// SET TIME ZONE
// -------------
putenv('TZ=US/Mountain');
?>
		<div id="primary">
			<div id="content" role="main">

				<?php // get_template_part( 'content', 'page' ); ?>
			
				<article class="page type-page status-publish hentry p-4 content-area md:w-4/5 mx-auto">
					<header class="entry-header">
						<h1 class="entry-title text-5xl"><?php the_title(); ?></h1>

					</header><!-- .entry-header -->
					<div class="entry-content">
						
<?php 
	define('MYSQL_HOST', 'localhost');
	define('MYSQL_USER', 'fcrc_wp');
	define('MYSQL_PASS', 'SgfmcjP077'); // password for MySQL database
	define('MYSQL_DB_MEMBERSHIP', 'fcrc_membership');
	define('MYSQL_DB_TORTOISE_HARE', 'fcrc_tortoise_hare');
	define('RACE_DATES_TABLE', 'race_dates');
	define('RACE_REGISTRATIONS_TABLE', 'race_registrations');
	define('PASSWORD', 'T&H*0525');   // password to allow administration of membership
	define('FIRST_YEAR', '2014'); /* for listing date range for expired memberships. */
	include '/home/fcrc/public_html/scripts/function-alias.php';
	if ($_GET['password'] != '') { $password = $_GET['password']; }
	else { $password = $_POST['password']; }
	if ($_GET['race_date'] != '') { $race_date = $_GET['race_date']; }
	else { $race_date = $_POST['race_date']; }
	

	function next_race() {
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB_TORTOISE_HARE);
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
		return ($next_race);
	}
							
	function introduction() {
		echo '<p>This script outputs the emails of all runners registered for a Tortoise & Hare race.  Authorized FCRC administrators can then copy and paste the addresses into an email to send to all runners .</p> ';
		$next_race = next_race();
		echo '<p>The next Tortoise & Hare race is the '.$next_race['location'].' '.$next_race['kilometers'].'k on '.$next_race['date'].'.</p>';
		echo '<p>For the T&H race you need email addresses of registered runners, enter the following:</p>';
		echo '<form action="" method="post" />';
		echo '<p>Race date: <input type="text" name="race_date" value="'.$next_race['date'].'" size="10" maxlength="10" /> (format: yyyy-mm-dd)</p>';
		echo '<p>Administrator password: <input type="password" name="password" value="" size="15" maxlength="15" /></p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';								
	}

	function password_error() {
		echo '<p>Incorrect password.  Please press the BACK button on your browser and try again.</p>';
		echo '<p>Please note that this tool is meant for authorized FCRC administrators only and not for general FCRC members.</p>';
	}

	function get_runners($date) { 
		// CONNECT TO MYSQL DATABASE:
		$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB_TORTOISE_HARE);
		if (mysqli_connect_error()) {
			echo "<p><i>Error: Unable to connect to database.<br />";
			echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
		}
		$query = 'SELECT `first name`,`last name` FROM `' .RACE_REGISTRATIONS_TABLE. '` WHERE `race date` = "'.$date.'" ORDER BY `first name` ASC';
		// echo '$query is ' . $query . '<br />';
		$num_data=0;
		$all_data = mysqli_query($link,$query);
		while ($row = mysqli_fetch_array($all_data)) {
			$runners[$num_data] = $row;		
			$num_data++;
		}
		mysqli_free_result($all_data);
		mysqli_close($link);
		if ($num_data>0) {
			echo '<p>There are <strong>'.$num_data.'</strong> registered runners for a Tortoise & Hare race held on <strong>'.$date.'</strong>.</p><p>Copy and paste the following into the <em>bcc:</em> field of an email message to send them a note.</p>';
		}
		else echo '<p>There are no runners registered for <strong>'.$date.'</strong>. Please check the date. If the date is invalid, you can hit the back button on your web browser and try again.</p>';
		// print_r($handicaps);
		return ($runners);
	}


	function get_email_address($first_name, $last_name) {
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
		return ($member_data[0]['email']);
	}	
	
	function output_email_addresses($runners) {
		$num_runners = 0;
		foreach ($runners as $runner) {
			$num_runners++;
		}
		for ($i=0; $i<$num_runners; $i++){
			$runners[$i]['email'] = get_email_address($runners[$i]['first name'],$runners[$i]['last name']);
			if ($runners[$i]['email'] != '') {
				echo $runners[$i]['first name'].' '.$runners[$i]['last name'].' &#60;'.$runners[$i]['email'].'&#62;';
			}
			if ($i<($num_runners-1) && $runners[$i]['email'] != '') { /* if not the last registered runner and we have an email address for runner*/
				echo '; ';
			}
		}								
	}
	
	
	// BEGIN MAIN PROGRAM

	if ($password=='') {
		introduction();
	}
	elseif ($password != PASSWORD) {
		password_error();
	}
	else { /* output emails */
		$runners = get_runners($race_date);
		output_email_addresses($runners);
	}
?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>