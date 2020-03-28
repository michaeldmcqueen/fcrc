<?php
/*
Template Name: TH Enter Predicted Time
*/


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
							define('MYSQL_HOST', 'localhost');
							define('MYSQL_USER', 'fcrc_wp');
							define('MYSQL_PASS', 'SgfmcjP077');
							define('MYSQL_DB', 'fcrc_tortoise_hare');
							define('FIRST_YEAR_IN_DB', '2011');
							define('FIRST_MONTH_IN_TH_SEASON', 'October');
							
							/* DETERMINE CURRENT SERIES */
								define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
								$today = date("m-d");
								if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
								else { $current_series = date("Y")-1; }
							/* end DETERMINE CURRENT SERIES */
							
							define('PREDICTION_DB', 'manual_predictions');
							if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
								else { $first_name = $_POST['first_name']; } 
							if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
								else { $last_name = $_POST['last_name']; }
							$predicted_hr = $_POST['predicted_hr'];
							$predicted_min = $_POST['predicted_min'];
							$predicted_sec = $_POST['predicted_sec'];
							$predicted_distance = $_POST['predicted_distance']; // in miles
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");

							function introduction() {
								global $current_series;
								echo '<p>If you are a new runner intending to run the next <a href="/tortoise-hare/">Tortoise & Hare</a> race, ';
								echo 'you can use this form to enter a predicted time.</p>';
								echo '<p>First, let\'s check to see that we do not already have race data for you from the last two T&H seasons; i.e., since '.FIRST_MONTH_IN_TH_SEASON.' '.($current_series-1).'. (If we do, you do not need to submit a predicted time.)</p>';

							}
							
							function ask_for_name() {
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}
							
							function ask_for_distance() {
								global $first_name, $last_name;
								echo '<form action="?first_name='. $first_name . '&last_name=' .$last_name.'" method="post" />';
								echo '<p>This calculator uses data from ONLY this <a href="/tortoise-hare/">T&H</a>'; 
								echo ' season and/or last season. Older data above is only listed for your reference.</p>';
								echo '<p>Enter race distance to predict time for: <input type="text" name="distance" value="" size="6" maxlength="6" /> kilometers</p>';
								echo '<p>(Tip: 10 miles=16.093 km; half-marathon=21.097 km; marathon=42.195 km)</p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
								}


							function get_data($series) {
								global $first_name, $last_name;
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `' .$series. '` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%" ORDER BY "date" ASC';
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


							
							function get_data_from_all_series() {
								global $all_data, $current_series;
								for ($year=FIRST_YEAR_IN_DB; $year<=$current_series; $year++) {
									if ($year==FIRST_YEAR_IN_DB) { $all_data=get_data($year); }
									else {
										$num_all_data=0;
										if ($all_data) {
											foreach ($all_data as $all_datum) {
												$num_all_data++;
											}
										}
										$temp_data=get_data($year);
										if ($temp_data) {
											foreach ($temp_data as $temp_datum) {
												$all_data[$num_all_data]=$temp_datum;
												$num_all_data++;
											}
										}
									}
								}
							}
							

							function print_data() {
								global $all_data, $first_name, $last_name, $data_last2years, $current_series;
								if ($all_data == NULL) {
									echo '<p>Since we do not have data from the last two T&H seasons (i.e., since '.FIRST_MONTH_IN_TH_SEASON.' '.($current_series-1). '), you will need to input a predicted time for the next T&H race.</p>';
									ask_for_predicted_time();
								}
								else {
									echo '<p>For ' . $first_name . ' ' . $last_name . ', we have the following race data: </p>';
									echo '<table><tr><th>Date</th><th>Location</th><th>Distance</th><th>Time</th><th>Pace</th></tr>';
									foreach ($all_data as $all_datum) {
										echo '<tr><td><a href="/results/?date=';
										echo $all_datum['date'].'">'.$all_datum['date'].'</a></td><td>'.$all_datum['location'].'</td>';
										echo '<td>'.$all_datum['distance'].' km</td><td>'.$all_datum['time'].'</td>';
										echo '<td>'.$all_datum['pace'].'/mi</td></tr>';
									}
									echo '</table>';
									get_data_last2years();
									if ($data_last2years[0]=='') {
										echo '<p>However, since we do not have data for you from the last two T&H seasons (i.e., since '.FIRST_MONTH_IN_TH_SEASON.' '.($current_series-1). '), <strong>you need to input a predicted time for the next T&H race</strong>.</p>';
										ask_for_predicted_time();
									}
									else {
										echo '<p>Since we have data for you from the last two T&H seasons (i.e., since '.FIRST_MONTH_IN_TH_SEASON.' '.($current_series-1). '), you <strong>do not</strong> need to input a predicted time for the next T&H race.</p>';
									}
								}
							}
							
							function ask_for_predicted_time() {
								global $first_name, $last_name;
								$first_name = ucwords(strtolower($first_name));
								$last_name = ucwords(strtolower($last_name));
								echo '<p>Please enter a predicted time for a hypothetical distance. <strong>Please do not sandbag.</strong></p>'; 
								echo '<p><em>Suggestion: Enter the results from a recent race or last hard run (e.g., 00:29:34 for 3.1 miles).  The time you enter will be adjusted automatically using our exclusive prediction algorithm for the next T&H race you run in.</em></p>';
								echo '<form action="?first_name='. $first_name . '&last_name=' .$last_name.'" method="post" />';
								echo '<p><strong>' . $first_name . ' ' .$last_name . '</strong>\'s predicted time: <input type="text" name="predicted_hr" size="1" maxlength="1" value="0" />:<input type="text" name="predicted_min" value="" size="2" maxlength="2" min="0" max="59" step="1" />:<input type="text" name="predicted_sec" value="" size="2" maxlength="2" min="0" max="59"  step="1" /> (hh:mm:ss)<br /> for a ';
								echo 'distance of: <input type="text" name="predicted_distance" value="" size="4" maxlength="5" min="0" max="100" /> <strong>miles</strong></p>';
								echo '<input type="submit" name="Submit" value=" Submit " /><br />';
								echo '<p><em>Note: If you have formerly entered a predicted time, your latest predicted time will be used and the other previously entered predictions ignored.</em></p>';
							}

							function store_predicted_time() {
								global $predicted_hr, $predicted_min, $predicted_sec, $predicted_distance, $first_name, $last_name;
								$today = date("Y-m-d");
								if (!is_numeric($predicted_hr) || !is_numeric($predicted_min) || !is_numeric($predicted_sec)) echo '<p>You entered an invalid time of '.$predicted_hr.':'.$predicted_min.':'.$predicted_sec.'.</p><p>Please click the back button on your web browser and try again.</p>';
								elseif (!is_numeric($predicted_distance)) echo '<p>You entered an invalid distance of '.$predicted_distance.' miles.</p><p>Please click the back button on your web browser and try again.</p>';
								elseif ( !($predicted_hr>='0' && $predicted_hr<='9') || !($predicted_min>='0' && $predicted_min<='59') || !($predicted_sec>='0' && $predicted_sec<='59') ) echo '<p>You entered an invalid time of '.$predicted_hr.':'.$predicted_min.':'.$predicted_sec.'.</p><p>Please click the back button on your web browser and try again.</p>';
								else {								
									$predicted_distance_in_km = $predicted_distance/0.621371192;
									$predicted_time_in_seconds = $predicted_hr*3600 + $predicted_min*60 + $predicted_sec;
									$predicted_pace_in_seconds_per_mile = $predicted_time_in_seconds / $predicted_distance;
									$predicted_pace_hhmmss_per_mile = format_hhmmss($predicted_pace_in_seconds_per_mile);
									$predicted_time = format_hhmmss($predicted_time_in_seconds);
									if ( !($predicted_pace_hhmmss_per_mile>='00:04:00' && $predicted_pace_hhmmss_per_mile<'00:20:00') ) echo '<p>You entered a time of '.$predicted_hr.':'.$predicted_min.':'.$predicted_sec.' for '.$predicted_distance.' miles, which corresponds to a pace of '.$predicted_pace_hhmmss_per_mile.'.  That does not seem like a valid pace.</p><p>Please click the back button on your web browser and try again.</p>';								
									else {
										// CONNECT TO MYSQL DATABASE:
										$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
										if (mysqli_connect_error()) {
											echo "<p><i>Error: Unable to connect to database.<br />";
											echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
										}										
										$query = "INSERT INTO `" . PREDICTION_DB . "` (`id` , `date` , `distance` , `first name`, `last name`, `predicted`) VALUES (NULL, '";
										$query = $query. $today."', '".$predicted_distance_in_km."', '".mysqli_real_escape_string($link,$first_name)."', '".mysqli_real_escape_string($link,$last_name)."', '".$predicted_time."')";
										/* echo $query .'<br />'; */
										mysqli_query($link,$query) or die('MySQL data entry failed.'); 
										mysqli_close($link);
										echo '<p>Successfully entered a predicted time of ' . $predicted_time . ' for ' . $predicted_distance . ' miles (' . $predicted_distance_in_km . ' km) for '.$first_name.' '.$last_name . ' into the database. This corresponds to a pace of '.substr($predicted_pace_hhmmss_per_mile,3).'/mile for that distance.</p>';
										echo '<p>If the above does not look right to you, hit the back button on your web browser and try again.  Otherwise, <strong>continue registering</strong> for the next Tortoise & Hare race by <a href="/register-for-next-th-race/?first_name='.$first_name.'&last_name='.$last_name.'"><strong>clicking here</strong></a>.';
									}
								}
							}
							
							function get_data_last2years() {
								global $data_last2years, $current_series;
								for ($year=$current_series-1; $year<=$current_series; $year++) {
									if ($year==$current_series-1) { $data_last2years=get_data($year); }
									elseif (is_array($data_last2years)) {
										$num_data_last2years=0;
										foreach ($data_last2years as $all_datum) {
											$num_data_last2years++;
										}
										$temp_data=get_data($year);
										foreach ($temp_data as $temp_datum) {
											$data_last2years[$num_data_last2years]=$temp_datum;
											$num_data_last2years++;
										}
									}
								}
							}
							
							
							function calculate_pred_time() {
								global $first_name, $last_name, $data_last2years, $distance, $distance_in_km, $current_series;
								$num_previous_year_data = 0;
								$num_current_year_data = 0;
								$previous_year_data=get_data($current_series-1);
									foreach ($previous_year_data as $previous_year_datum) {
										$num_previous_year_data++;
									}
								$current_year_data=get_data($current_series);
									foreach ($current_year_data as $current_year_datum) {
										$num_current_year_data++;
									}
								$prediction_from_previous_year = prediction_from_data($previous_year_data);
								$prediction_from_current_year = prediction_from_data($current_year_data);
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
								if ($outputted_pace==0) {
									echo '<p>Sorry, since we do not have data for you from the last two Tortoise & Hare seasons, we cannot accurately predict your time.</p>';
									echo '<p>Try using the <a href="http://www.mcmillanrunning.com/calculator">McMillian Running Calculator</a> instead.</p>';
								}
								else {
									echo '<p>'.$first_name.' '.$last_name.', using the latest results in the FCRC database ';
									echo 'and our exclusive ';
									echo '<a href="/tortoise-hare/#algorithm">prediction algorithm</a>,';
									echo ' we predict your time for '.$distance_in_km.' kilometers will be '.format_hhmmss($outputted_time);
									echo ' ('.format_mmss($outputted_pace).'/mi).</p>';
								}
							}

							function prediction_from_data($data) { // $data being dataset from MySQL table (year)
								global $first_name, $last_name, $distance;
								$num_pred_paces=0;
								foreach ($data as $datum) {
									$datum_seconds = (substr($datum['time'],0,2)*3600+substr($datum['time'],3,2)*60+substr($datum['time'],6,2))/($datum['distance']*0.621371192);
									$datum_distance = $datum['distance']*0.621371192; //in miles
									$adj_factor = pow(1.05,((log($distance)-log($datum_distance))/log(2)));
									$pred_paces[$num_pred_paces] = $datum_seconds * $adj_factor;
									$num_pred_paces++;
								}
								foreach ($pred_paces as $pred_pace) {
									$sum_of_pred_paces = $sum_of_pred_paces + $pred_pace;
								}
								$final_pred_pace = $sum_of_pred_paces / $num_pred_paces;
								$final_pred_time = round($final_pred_pace * $distance);
								if ($num_pred_paces <= 2) {
									$prediction['pace'] = $final_pred_pace;
									$prediction['time'] = $final_pred_time;
								}
								else {
									//Calculation for alternative predicted time below
									$min_pred_pace = min($pred_paces);
									$max_pred_pace = max($pred_paces);
									$alt_final_pred_pace = round($min_pred_pace+($max_pred_pace-$min_pred_pace)/3);		
									$alt_final_pred_time = round($distance*($min_pred_pace+($max_pred_pace-$min_pred_pace)/3));
									$prediction['pace'] = min($final_pred_pace, $alt_final_pred_pace);
									$prediction['time'] = min($final_pred_time, $alt_final_pred_time);
								}
								return ($prediction);
							}
							
							
							function format_mmss($secs) {
							   $times = array(60, 1);
							   $time = '';
							   $tmp = '';
							   for($i = 0; $i < 2; $i++) {
								  $tmp = floor($secs / $times[$i]);
								  if($tmp < 1) {
									 $tmp = '00';
								  }
								  elseif($tmp < 10) {
									 $tmp = '0' . $tmp;
								  }
								  $time .= $tmp;
								  if($i < 1) {
									 $time .= ':';
								  }
								  $secs = $secs % $times[$i];
							   }
							   return $time;
							}
							
							function format_hhmmss($secs) {
							   $times = array(3600, 60, 1);
							   $time = '';
							   $tmp = '';
							   for($i = 0; $i < 3; $i++) {
								  $tmp = floor($secs / $times[$i]);
								  if($tmp < 1) {
									 $tmp = '00';
								  }
								  elseif($tmp < 10) {
									 $tmp = '0' . $tmp;
								  }
								  $time .= $tmp;
								  if($i < 2) {
									 $time .= ':';
								  }
								  $secs = $secs % $times[$i];
							   }
							   return $time;
							}
							
							// ******* BEGIN MAIN PROGRAM *******
							if ($last_name=='') { 
								introduction();
								ask_for_name(); 
							}
							elseif ($predicted_distance=='') { // user entered name already but not predicted time
								get_data_from_all_series();
								if ($distance==NULL) {
									print_data(); // will ask_for_predicted_time() if no data in DB from last 2 years
								}
							}
							else {
								store_predicted_time();
							}
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>