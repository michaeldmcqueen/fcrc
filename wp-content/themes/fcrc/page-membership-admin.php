<?php
/*
Template Name: Membership Administration
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
							define('MYSQL_DB', 'fcrc_membership');
							define('PASSWORD', 'T&H*0525');   // password to allow administration of membership
							define('FIRST_YEAR', '2014'); /* for listing date range for expired memberships. */
							$password = $_POST['password'];
							if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
							else { $first_name = $_POST['first_name']; }
							if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
							else { $last_name = $_POST['last_name']; }
							if ($_GET['function'] != '') { $function = $_GET['function']; }
							else { $function = $_POST['function']; }
							$board_member = $_POST['board_member'];
							$unsubscribe = $_POST['unsubscribe'];
							$street = $_POST['street'];
							$city = $_POST['city'];
							$state = $_POST['state'];
							$zip = $_POST['zip'];
							$phone = $_POST['phone'];
							$expiration = $_POST['expiration'];
							$email = $_POST['email'];
							$id = $_POST['id'];
							$family_head = $_POST['family_head'];
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");
							if ($_GET['start_year'] != '') { $start_year = $_GET['start_year']; }
							else { $start_year = $_POST['start_year']; }
							if ($_GET['start_month'] != '') { $start_month = $_GET['start_month']; }
							else { $start_month = $_POST['start_month']; }
							if ($_GET['start_day'] != '') { $start_day = $_GET['start_day']; }
							else { $start_day = $_POST['start_day']; }
							if ($_GET['end_year'] != '') { $end_year = $_GET['end_year']; }
							else { $end_year = $_POST['end_year']; }
							if ($_GET['end_month'] != '') { $end_month = $_GET['end_month']; }
							else { $end_month = $_POST['end_month']; }
							if ($_GET['end_day'] != '') { $end_day = $_GET['end_day']; }
							else { $end_day = $_POST['end_day']; }

							
							$months[1] = 'January';
							$months[2] = 'February';
							$months[3] = 'March';
							$months[4] = 'April';
							$months[5] = 'May';
							$months[6] = 'June';
							$months[7] = 'July';
							$months[8] = 'August';
							$months[9] = 'September';
							$months[10] = 'October';
							$months[11] = 'November';
							$months[12] = 'December';
							
							function introduction() {
								echo '<p>This is a script for membership administration. Authorized FCRC membership administrators can use it to add new members or revise member details, including membership expiration.</p> ';
								echo '<form action="" method="post" />';
								echo '<p>First, please enter the administrator password: <input type="password" name="password" value="" size="15" maxlength="15" /></p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';								
							}

							function password_error() {
								echo '<p>Incorrect password.  Please press the BACK button on your browser and try again.</p>';
								echo '<p>Please note that this tool is meant for authorized FCRC administrators only and not for general FCRC members.</p>';
							}

							function ask_for_new_member_info() {
								echo '<h2>Add New Member</h2>';
								echo '<p>Use this form for <strong>new Individual Memberships</strong> only. Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="30" maxlength="30" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="30" maxlength="30" /></p>';
								echo '<p>Board member (y=yes; n=no): <input type="text" name="board_member" value="n" size="1" maxlength="1"/></p>';
								echo '<p>Unsubscribe to newsletter: <input type="text" name="unsubscribe" value="n" size="1" maxlength="1" /></p>';
								echo '<p>Street: <input type="text" name="street" value="" size="40" maxlength="40" /></p>';
								echo '<p>City: <input type="text" name="city" value="" size="20" maxlength="20" /></p>';
								echo '<p>State: <input type="text" name="state" value="CO" size="2" maxlength="2" /></p>';
								echo '<p>Zip: <input type="number" name="zip" value="" size="5" maxlength="5" /></p>';
								echo '<p>Phone (xxx-xxx-xxxx): <input type="text" name="phone" value="" size="12" maxlength="12" /></p>';
								echo '<p>Expiration (yyyy-mm-dd): <input type="text" name="expiration" value="" size="10" maxlength="10" /></p>';
								echo '<p>E-mail: <input type="text" name="email" value="" size="40" maxlength="100" /></p>';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="add_new_member" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
								echo '</form>';
							}

							function ask_for_new_family_members_info() {
								echo '<h2>Add New Family Members</h2>';
								echo '<p>Use this form for Family Memberships only. Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>Head of family: <input type="text" name="family_head" value="" size="40" maxlength="40" /> (E.g., <i>John Smith</i>)</p>';
								echo '<p>Street: <input type="text" name="street" value="" size="40" maxlength="40" /></p>';
								echo '<p>City: <input type="text" name="city" value="" size="20" maxlength="20" /></p>';
								echo '<p>State: <input type="text" name="state" value="CO" size="2" maxlength="2" /></p>';
								echo '<p>Zip: <input type="number" name="zip" value="" size="5" maxlength="5" /></p>';
								echo '<p>Expiration (yyyy-mm-dd): <input type="text" name="expiration" value="" size="10" maxlength="10" /></p>';
								echo '<p>New family members, <strong>including Head of Family</strong> (if Head of Family is not already in database. If Head of Family is <em>already</em> in database, do not put the Head of Family in the list below. Instead, after completing this form with new family members, go back to the Membership Administrative page and edit the Family Head\'s membership and make sure that he or she is listed in the Head of Family field.):</p>';
								echo '<table><tr><th>First name</th><th>Last name</th><th>Phone (xxx-xxx-xxxx)</th><th>E-mail</th><th>Board member (y=yes; n=no)</th><th>Unsubscribe to newsletter</th></tr>';
								for ($h=0; $h<7; $h++) { //fields for new family members
									echo '<tr><td align="center"><input type="text" name="data['.$h.'][first_name]" value="" size="15" maxlength="15" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][last_name]" value="" size="15" maxlength="15" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][phone]" value="" size="12" maxlength="12" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][email]" value="" size="30" maxlength="30" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][board_member]" value="n" size="1" maxlength="1" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][unsubscribe]" value="n" size="1" maxlength="1" /></td></tr>';
								}
								echo '</table>';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="add_new_family_members" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
								echo '</form>';
							}
							
							function add_new_member_to_db() {
								global $first_name, $last_name, $board_member, $unsubscribe, $street, $city, $state, $zip, $phone, $expiration, $email;
							
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$today = date("Y-m-d");
								$query = "INSERT INTO `members` (`first name` , `last name` , `board_member` , `unsubscribe`, `street`, `city`, `state`, `zip`, `phone`, `expiration`, `email`, `first_joined`) VALUES ('";
								$query = $query. mysqli_real_escape_string($link,$first_name)."', '".mysqli_real_escape_string($link,$last_name)."', '".$board_member."', '".$unsubscribe."', '".$street."', '".$city."', '".$state."', '".$zip."', '".$phone."', '".$expiration."', '".$email."', '".$today."')";
								echo '<p>'.$query .'</p>'; 
								mysqli_query($link,$query) or die('MySQL data entry failed.'); 
								mysqli_close($link);
								echo '<p>Successfully entered data for ' .$first_name. ' '.$last_name.'.</p>';
								show_buttons();						
							}

							function add_new_family_members_to_db() {
								global $family_head, $street, $city, $state, $zip, $expiration;
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$today = date("Y-m-d");
								for ($i=0; $i<7; $i++) {
									if ($_POST['data'][$i]['first_name'] != '') {
										$data[$i]['first_name'] = stripslashes(trim($_POST['data'][$i]['first_name']));
										$data[$i]['last_name'] = stripslashes(trim($_POST['data'][$i]['last_name']));
										$data[$i]['phone'] = $_POST['data'][$i]['phone'];
										$data[$i]['email'] = $_POST['data'][$i]['email'];
										$data[$i]['board_member'] = $_POST['data'][$i]['board_member'];
										$data[$i]['unsubscribe'] = $_POST['data'][$i]['unsubscribe'];
										$query = "INSERT INTO `members` (`first name` , `last name` , `board_member` , `unsubscribe`, `street`, `city`, `state`, `zip`, `phone`, `expiration`, `family_head`, `email`, `first_joined`) VALUES ('";
										$query = $query. mysqli_real_escape_string($link,$data[$i]['first_name'])."', '".mysqli_real_escape_string($link,$data[$i]['last_name'])."', '".$data[$i]['board_member']."', '".$data[$i]['unsubscribe']."', '".$street."', '".$city."', '".$state."', '".$zip."', '".$data[$i]['phone']."', '".$expiration."', '".$family_head."', '".$data[$i]['email']."', '".$today."')";
										echo '<p>'.$query .'</p>'; 
										mysqli_query($link,$query) or die('MySQL data entry failed.'); 
										echo '<p>Successfully entered data for ' .$data[$i]['first_name']. ' '.$data[$i]['last_name'].'.</p>';
									}
								}
								mysqli_close($link);
								show_buttons();						
							}

							function ask_for_member_to_edit() {
								echo '<h2>Edit Existing Member</h2>';
								echo '<p>Please enter the following information:</p>';
								echo '<form action="./?function=edit_member" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}


							
							function edit_member() {
								global $first_name, $last_name, $data;
								get_data();
								if ( $data[0]['first name']=='' ) {
									echo '<p>Sorry, there is no one of the name '.$first_name.' '.$last_name.' in the database. Please press the BACK button of your browser and try again.</p>';
								}
								else {
									echo '<p>This is the page for editing info for '.$data[0]['first name'].' '.$data[0]['last name'].'.</p>';
									echo '<form action="." method="post" />';
									echo '<p>First name: <input type="text" name="first_name" value="'.$data[0]['first name'].'" size="30" maxlength="30" /></p>';
									echo '<p>Last name: <input type="text" name="last_name" value="'.$data[0]['last name'].'" size="30" maxlength="30" /></p>';
									echo '<p>Head of family: <input type="text" name="family_head" value="'.$data[0]['family_head'].'" size="40" maxlength="40" />(E.g., <i>John Smith</i> for family memberships; empty if individual membership. Value should be the same for every member of that family.)</p>';
									echo '<p>Board member: <input type="text" name="board_member" value="';
									if ( strpos(strtolower($data[0]['board_member']),'y') === FALSE ) {echo 'n';}
									else { echo 'y'; }
									echo '" size="1" maxlength="1"/></p>';
									echo '<p>Unsubscribe to newsletter: <input type="text" name="unsubscribe" value="';
									if ( strpos(strtolower($data[0]['unsubscribe']),'y') === FALSE ) {echo 'n';}
									else { echo 'y'; }
									echo '" size="1" maxlength="1"/></p>';
									echo '<p>Street: <input type="text" name="street" value="'.$data[0]['street'].'" size="40" maxlength="40" /></p>';
									echo '<p>City: <input type="text" name="city" value="'.$data[0]['city'].'" size="20" maxlength="20" /></p>';
									echo '<p>State: <input type="text" name="state" value="'.$data[0]['state'].'" size="2" maxlength="2" /></p>';
									echo '<p>Zip: <input type="number" name="zip" value="'.$data[0]['zip'].'" size="5" maxlength="5" /></p>';
									echo '<p>Phone (xxx-xxx-xxxx): <input type="text" name="phone" value="'.$data[0]['phone'].'" size="12" maxlength="12" /></p>';
									echo '<p>First joined: ' .$data[0]['first_joined'] . '</p>';
									echo '<p>Expiration (yyyy-mm-dd): <input type="text" name="expiration" value="'.$data[0]['expiration'].'" size="10" maxlength="10" /></p>';
									echo '<p>E-mail: <input type="text" name="email" value="'.$data[0]['email'].'" size="40" maxlength="100" /></p>';
									echo '<input type="hidden" name="id" value="'.$data[0]['id'].'" />';
									echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
									echo '<input type="hidden" name="function" value="edit_member_in_db" />';
									echo '<input type="submit" name="Submit" value=" Submit " />';
									echo '</form>';
								}
							}

							function edit_member_in_db() {
								global $first_name, $last_name, $board_member, $unsubscribe, $street, $city, $state, $zip, $phone, $expiration, $email, $id, $family_head;
								/* need to code check if already exists */
							
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'UPDATE `members` set `first name`="' .mysqli_real_escape_string($link,$first_name). '", `last name`="' .mysqli_real_escape_string($link,$last_name). '", `board_member`="' .$board_member. '", `unsubscribe`="' .$unsubscribe. '", `street`="' .$street. '", `city`="' .$city . '", `state`="' .$state. '", `zip`="' .$zip. '",  `phone`="' .$phone. '", `expiration`="' .$expiration. '", `email`="' .$email. '", `family_head`="' .$family_head. '" WHERE `id`="' .$id. '"';
								/* echo '<p>' .$query .'</p>'; */
								mysqli_query($link,$query) or die('MySQL data entry failed.'); 
								mysqli_close($link);
								echo '<p><strong>Successfully edited data for ' .$first_name. ' '.$last_name.'.</strong> [<a href="./?function=edit_member&first_name='.$first_name.'&last_name='.$last_name.'">Re-edit</a>]</p>';
								get_data();
								print_data();
								show_buttons();
							}
							
							function get_data() {
								global $first_name, $last_name, $data;
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"';
								// echo '$query is ' . $query . '<br />';
								$num_data=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
								mysqli_close($link);
							}
							
							function show_roster() {
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `members` ORDER BY `first name` ASC';
								$num_members=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$roster[$num_members] = $row;		
									$num_members++;
								}
								mysqli_free_result($all_data);
								mysqli_close($link);
								$today = date("Y-m-d");
								$num_expired_members = 0;
								foreach ($roster as $member) {
									if ($member['expiration']<$today && strpos($member['board_member'],'y')===FALSE) { $num_expired_members++; }
								}
								echo '<p>There are '.$num_members.' members in the database: ' .($num_members - $num_expired_members). ' members in <a href="./?function=list_active_members">good standing</a> and ' .$num_expired_members. ' with <a href="./?function=list_expired_memberships">expired memberships</a>.</p>';
								for ($i=0; $i<$num_members; $i++){
									echo ($i+1).'. <a href="./?function=edit_member&first_name='.$roster[$i]['first name'].'&last_name='.$roster[$i]['last name'].'">'.$roster[$i]['first name'].' '.$roster[$i]['last name'].'</a><br />';
								}
								show_newsletter_emails_button();
								show_add_member_button();
								show_add_family_members_button();
								show_list_expired_memberships_button();
								export_CSV_button();
								database_edit_button();
							}

							function convert_two_digit_string_to_int($num) {
								if (substr($num, 0, 1) == '0') {
								$num = substr($num, 1, 1);
								}
								return $num;
							}
							
							
							
							
							function get_date_range() {  /* GET DATE RANGE FOR EXPIRED MEMBERSHIPS */
								date_default_timezone_set('America/Denver');
								global $months;
								$today_year = date("Y");
								$today_day = date("d");
								$today_month_num = date("m");
								$today_month_num = convert_two_digit_string_to_int($today_month_num);	
								$today_month = $months[$today_month_num];					
								$selected_start_month = $today_month;
								$selected_start_day = 1;
								$selected_start_year = $today_year;
								$selected_end_month = $today_month;
								$selected_end_day = $today_day;
								$selected_end_year = $today_year;
								echo '<h2>List Expired Memberships</h2>';
								echo '<p>List expired memberships from:</p>';
								echo '<p><form action="./?function=list_expired_memberships" method="post" />';
								echo 'Start date: <select name="start_month" style="width: 125px">';
									foreach ($months as $month) {
									echo '<option ';
									if ($month == $selected_start_month) {echo 'selected="selected" ';}
									echo 'value="' . $month . '">';
									echo $month;
									echo '</option>';
								}
								echo '</select> <select name="start_day" style="width: 47px">';
								for ($day = 1; $day <= 31; $day++) {
									echo '<option ';
									if ($day == $selected_start_day) {echo 'selected="selected" ';}
									echo 'value="' . $day . '">';
									echo $day;
									echo '</option>';
								}
								echo '</select> <select name="start_year" width="60" style="width: 70px">';
								for ($year = $today_year+3; $year >= FIRST_YEAR; $year--) {
									echo '<option ';
									if ($year == $selected_start_year) {echo 'selected="selected" ';}
									echo 'value="' . $year . '">';
									echo $year;
									echo '</option>';
								}
							echo '</select><br />to:<br />End date: </td><td><select name="end_month" style="width: 125px">';
								foreach ($months as $month) {
									echo '<option ';
									if ($month == $selected_end_month) {echo 'selected="selected" ';}
									echo 'value="' . $month . '">';
									echo $month;
									echo '</option>';
								}
								echo '</select> <select name="end_day" style="width: 47px">';
								for ($day = 1; $day <= 31; $day++) {
									echo '<option ';
									if ($day == $selected_end_day) {echo 'selected="selected" ';}
									echo 'value="' . $day . '">';
									echo $day;
									echo '</option>';
								}
								echo '</select> <select name="end_year" width="60" style="width: 70px">';
								for ($year = $today_year+3; $year >= FIRST_YEAR; $year--) {
									echo '<option ';
									if ($year == $selected_end_year) {echo 'selected="selected" ';}
									echo 'value="' . $year . '">';
									echo $year;
									echo '</option>';
								}
								echo '</select></p><input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}
							
							
							function list_expired_memberships() {
								echo '<h2>Expired Memberships</h2>';
								$today = date("Y-m-d");
								global $start_year, $start_month, $start_day, $end_year, $end_month, $end_day, $months;
								if ($start_year == '') { 
									$start_date = '0000-00-00';
									$end_date = $today; 
								}
								else {
									/* Convert start_month to start_month_number */
									for ($i=1; $i<=12; $i++) {
										if ($start_month == $months[$i]) {
											$start_month_num = $i;
											break;
										}
									}
									/* Convert end_month to end_month_number */
									for ($i=1; $i<=12; $i++) {
										if ($end_month == $months[$i]) {
											$end_month_num = $i;
											break;
										}
									}
									$start_date = $start_year . '-';
									if ( $start_month_num < 10 ) { $start_date = $start_date . '0' . $start_month_num . '-'; }
									else { $start_date = $start_date . '0' . $start_month_num . '-'; }
									if ( $start_day_num < 10 ) { $start_date = $start_date . '0' . $start_day; }
									else { $start_date = $start_date . $start_day; }
									$end_date = $end_year . '-';
									if ( $end_month_num < 10 ) { $end_date = $end_date . '0' . $end_month_num . '-'; }
									else { $end_date = $end_date . $end_month_num . '-'; }
									if ( $end_day < 10 ) { $end_date = $end_date . '0' . $end_day; }
									else { $end_date = $end_date . $end_day; }
								}

								if ($end_date < $start_date) {
									echo '<p><strong>Error: End date cannot be before start date.  Please <a href="./?function=get_date_range">try again</a>.</strong>';
								}
								else {
									$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
									if (mysqli_connect_error()) {
										echo "<p><i>Error: Unable to connect to database.<br />";
										echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
									}
									$query = 'SELECT * FROM `members` WHERE `expiration`>="' .$start_date. '" AND `expiration`<="' .$end_date. '" AND `board_member` NOT LIKE "%y%" ORDER BY `first name` ASC';
									$num_expired_memberships=0;
									$all_data = mysqli_query($link,$query);
									while ($row = mysqli_fetch_array($all_data)) {
										$expired_memberships[$num_expired_memberships] = $row;		
										$num_expired_memberships++;
									}
									mysqli_free_result($all_data);
									mysqli_close($link);
									echo '<p>There are '.$num_expired_memberships.' expired memberships in the database';
									if ($start_year != '') {
										echo ' from ' .$start_month. ' ' .$start_day. ', ' .$start_year. ' to ' .$end_month. ' ' .$end_day. ', ' .$end_year;
									}
									echo ', including:</p><p>';
									for ($i=0; $i<$num_expired_memberships; $i++){
										echo ($i+1).'. <a href="./?function=edit_member&first_name='.$expired_memberships[$i]['first name'].'&last_name='.$expired_memberships[$i]['last name'].'">'.$expired_memberships[$i]['first name'].' '.$expired_memberships[$i]['last name'].'</a> ('.$expired_memberships[$i]['expiration']. ')<br />';
									}
									echo '</p><p>Their e-mail addresses are:</p><p><em>';
									$num_expired_members_with_no_email = 0;
									for ($i=0; $i<$num_expired_memberships; $i++){
										if ($expired_memberships[$i]['email']!='') echo /* $expired_memberships[$i]['first name'].' '.$expired_memberships[$i]['last name'].' '.*/ $expired_memberships[$i]['email'].'; ';
										else {
											$expired_members_with_no_email[$num_expired_members_with_no_email] = $expired_memberships[$i];
											$num_expired_members_with_no_email++;
										}
									}
									echo '</em></p>';
									echo '<p>You can copy and paste the e-mail address above into your e-mail application to send the members a reminder.</p>';
									echo '<p>Also note that the following members\' membership expired but they do not have email addresses:</p>';
									foreach ($expired_members_with_no_email as $expired_member_with_no_email) {
										echo $expired_member_with_no_email['first name'].' '.$expired_member_with_no_email['last name'].' <br />';
									}
									show_buttons();
								}
							}
							
							function list_active_members() {
								echo '<h2>Active Memberships</h2>';
								$today = date("Y-m-d");
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `members` WHERE `expiration`>="' .$today. '" OR `board_member` LIKE "%y%" ORDER BY `first name` ASC';
								$num_active_memberships=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$active_memberships[$num_active_memberships] = $row;		
									$num_active_memberships++;
								}
								mysqli_free_result($all_data);
								mysqli_close($link);
								echo '<p>There are '.$num_active_memberships.' active members in the database, including:</p>';
								for ($i=0; $i<$num_active_memberships; $i++){
									echo ($i+1).'. <a href="./?function=edit_member&first_name='.$active_memberships[$i]['first name'].'&last_name='.$active_memberships[$i]['last name'].'">'.$active_memberships[$i]['first name'].' '.$active_memberships[$i]['last name'].'</a> ('.$active_memberships[$i]['expiration']. ')<br />';
								}
								echo '<br /><p>The e-mail addresses for all active memberships are:</p><p></em>';
								for ($i=0; $i<$num_active_memberships; $i++){
									echo $active_memberships[$i]['first name'].' '.$active_memberships[$i]['last name'].' &#60;'.$active_memberships[$i]['email'].'&#62;';
									if ($i<($num_active_memberships-1)) { /* if not the last expired membership */
										echo '; ';
									}
								}								
								show_buttons();
							}

							function show_newsletter_emails() {
								echo '<h2>Email Addresses Subscribed to Newsletter</h2>';
								$today = date("Y-m-d");
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `members` WHERE `expiration`>="' .$today. '" AND `unsubscribe` LIKE "%n%" AND `email` NOT LIKE "" ORDER BY `first name` ASC';
								$num_newsletter_emails=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$newsletter_emails[$num_newsletter_emails] = $row;		
									$num_newsletter_emails++;
								}
								mysqli_free_result($all_data);
								mysqli_close($link);
								echo '<p>There are '.$num_newsletter_emails.' email addresses subscribed to the newsletter (and are active members).</p>';
							/*	for ($i=0; $i<$num_newsletter_emails; $i++){
									echo ($i+1).'. <a href="./?function=edit_member&first_name='.$newsletter_emails[$i]['first name'].'&last_name='.$newsletter_emails[$i]['last name'].'">'.$newsletter_emails[$i]['first name'].' '.$newsletter_emails[$i]['last name'].'</a> ('.$newsletter_emails[$i]['expiration']. ')<br />';
								} */
								echo '<p>The e-mail addresses to send newsletters to are:</p><p></em>';
								for ($i=0; $i<$num_newsletter_emails; $i++){
									echo $newsletter_emails[$i]['first name'].' '.$newsletter_emails[$i]['last name'].' &#60;'.$newsletter_emails[$i]['email'].'&#62;';
									if ($i<($num_newsletter_emails-1)) { /* if not the last expired membership */
										echo '; ';
									}
								}								
								show_buttons();
							}
							
							
							function print_data() {
								global $data, $first_name, $last_name;
								echo '<p>First name: ' . $data[0]['first name'];
								echo '<br />Last name: ' . $data[0]['last name'];
								echo '<br />Type of membership: ';
								if ($data[0]['family_head']!='') {
									echo 'Family<br />Family head: '.$data[0]['family_head'];
								}
								else {echo 'Individual';}
								echo '<br />Board Member: ';
								$board_member = (strpos(strtolower($data[0]['board_member']),'y') !== FALSE);
								if ($board_member) { echo 'yes'; }
								else { echo 'no'; }
								echo '<br />Unsubscribe to newsletter: ' . $data[0]['unsubscribe'];
								$unsubscribe = (strpos(strtolower($data[0]['unsubscribe']),'y') !== FALSE);
								if ($unsubscribe) { echo 'yes'; }
								else { echo 'no'; }
								echo '<br />Address: ';
								echo $data[0]['street'] . ', ' . $data[0]['city'] . ', ' . $data[0]['state'] . ', ' . $data[0]['zip'];
								echo '<br />Phone: ' . $data[0]['phone'];
								echo '<br />E-mail: ' . $data[0]['email'];
								echo '<br />Expiration: ' . $data[0]['expiration'] . '</p>';
								$today = date("Y-m-d");
								if ($today < $data[0]['expiration']) { echo '<p>Membership for ' .$first_name. ' ' .$last_name. ' is in good standing.</p>'; }
								elseif ($board_member) {echo '<p>Although membership expired, ' .$first_name. ' ' .$last_name. ' is a board member and does not need to renew it. '; }
								else { echo '<p><strong>Membership for ' .$first_name. ' ' .$last_name. ' expired!</strong></p>'; }
							}
							
					
							
							
							function show_buttons() {
								show_newsletter_emails_button();
								show_roster_button();
								show_edit_member_button();
								show_add_member_button();
								show_add_family_members_button();
								show_list_expired_memberships_button();
								export_CSV_button();
								database_edit_button();
							}

							function show_newsletter_emails_button() {
								/*---BUTTON FOR SHOWING ROSTER---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="show_newsletter_emails" />';
								echo '<input type="submit" name="show_newsletter_emails" value=" List Newsletter Email Addresses " />';
								echo '</form></p>'; 						
							}
							
							function show_roster_button() {
								/*---BUTTON FOR SHOWING ROSTER---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="show_roster" />';
								echo '<input type="submit" name="show_roster" value=" Show Roster " />';
								echo '</form></p>'; 						
							}
							
							
							function show_add_member_button() {
								/*---BUTTON FOR ADDING NEW MEMBER---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="add_new_member" />';
								echo '<input type="submit" name="add_new_member" value=" Add New Member " /> Use for new Individual Memberships only.';
								echo '</form></p>'; 						
							}

							function show_add_family_members_button() {
								/*---BUTTON FOR ADDING NEW MEMBER---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="add_new_family_members" />';
								echo '<input type="submit" name="add_new_family_members" value=" Add New Family Members " /> Use to add new Family Membership or family members.';
								echo '</form></p>'; 						
							}							
							
							function show_edit_member_button() {
								/*---BUTTON FOR EDITING EXISTING MEMBER---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="ask_for_member_to_edit" />';
								echo '<input type="submit" name="ask_for_member_to_edit" value=" Edit Member " />';
								echo '</form></p>'; 						
							}
							
							function show_list_expired_memberships_button() {
								/*---BUTTON FOR LISTING EXPIRED MEMBERSHIPS---*/
								echo '<p><form action="." method="post" />';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="get_date_range" />';
								echo '<input type="submit" name="list_expired_memberships" value=" List Expired Memberships " />';
								echo '</form></p>'; 						
							}
							
							function database_edit_button() {
								/*---BUTTON FOR LISTING EXPIRED MEMBERSHIPS---*/
								echo '<p><form action="							http://fortcollinsrunningclub.org:2082/cpsess3034677444/3rdparty/phpMyAdmin/index.php?post_login=14319277616577#PMAURL-2:sql.php?db=fcrc_membership&table=members&server=1&target=&token=d4609fe93bc997fc54720df33c47957e" target="_blank" method="post" />';
								echo '<input type="submit" name="database_edit" value=" Manually Edit Database " /> (username: <b>fcrc</b> ; password: [same as what you used to login into membership admin])';
								echo '</form></p>'; 						
							}
							
							function export_CSV_button() {
								/*---BUTTON FOR EXPORTING CURRENT ROSTER TO .CSV FILE---*/
								echo '<p><form action="http://fortcollinsrunningclub.org/scripts/membership_roster_output.php" method="post" />';
								echo '<input type="submit" name="export_roster_to_csv" value=" Export Roster to .CSV File " /> (e.g., to send to Colorado Runner Magazine)';
								echo '</form></p>'; 								
							}


							
							// BEGIN MAIN PROGRAM

							if ($password=='' && $function!='edit_member' && $function!='list_active_members' && $function!='list_expired_memberships' && $function!='get_date_range') {
								introduction();
							}
							elseif ($password != PASSWORD && $function!='edit_member' && $function!='list_active_members' && $function!='list_expired_memberships' && $function!='get_date_range') {
								//echo '<p>Password entered is '.$password.'</p>';
								password_error();
							}
							else { /* user can now enter Admin panel */
								if ($function=='') {
									echo '<p>Password entered correctly.  You are authorized to perform any of the following tasks.</p>';
									show_buttons();
								}
								elseif ($function=='show_newsletter_emails') { show_newsletter_emails(); }
								elseif ($function=='show_roster') { show_roster(); }
								elseif ($function=='add_new_member') { 
									if ($first_name=='' && $last_name=='') { ask_for_new_member_info(); }
									else { add_new_member_to_db(); }
								} 
								elseif ($function=='add_new_family_members') { 
									if ($family_head=='') { ask_for_new_family_members_info(); }
									else { add_new_family_members_to_db(); }
								} 
								elseif ($function=='edit_member') { edit_member(); }
								elseif ($function=='edit_member_in_db') { edit_member_in_db(); }
								elseif ($function=='ask_for_member_to_edit') { ask_for_member_to_edit(); }
								elseif ($function=='get_date_range') { get_date_range(); }
								elseif ($function=='list_expired_memberships') { list_expired_memberships(); }
								elseif ($function=='list_active_members') { list_active_members(); }

							}
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>