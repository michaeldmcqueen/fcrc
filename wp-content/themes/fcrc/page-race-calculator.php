<?php
/*
Template Name: Race Calculator
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
							/* DETERMINE CURRENT SERIES */
								define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
								$today = date("m-d");
								if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
								else { $current_series = date("Y")-1; }
							/* end DETERMINE CURRENT SERIES */
							if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
								else { $first_name = $_POST['first_name']; } 
							if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
								else { $last_name = $_POST['last_name']; } 
							$distance_in_km = $_POST['distance'];
							$distance = $distance_in_km *0.621371192;
							$first_name = ucwords(strtolower(stripslashes($first_name)));
							$last_name = ucwords(strtolower(stripslashes($last_name)));

							function introduction() {
								echo '<p>This calculator will predict your finish time for a future race of a specified distance ';
								echo ' based off of your times in our <a href="/tortoise-hare/">Tortoise';
								echo ' & Hare</a> database (assuming that you did any T&H races this year).';
								echo ' <strong>You can also use it to list your times from past races in our database.</strong></p>';
								echo '<p>This calculator uses the same algorithm that the race director uses to predict your time for your next T&H race.';
								echo '</p>';
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
								// echo '$query is ' . $query . '<br />';
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
								global $all_data, $first_name, $last_name, $current_series;
								if ($all_data == NULL) {
									echo '<p>Sorry, no one of the name "' . $first_name . ' ' . $last_name;
									echo '" could be found in our database. Please enter another name.</p>';
									echo '<p>Note: perhaps your name is spelled differently in our database. This could be the case if you have a nickname, have a \' or space in your name, or have changed names recently.</p>';
									ask_for_name();
									echo '<br /><p>If you are not in our database (because, for example, you did not do a Tortoise & Hare race in 2011 or later), try using the <a href="http://www.mcmillanrunning.com/calculator">McMillian Running Calculator</a> instead.</p>';
								}
								else {
									echo '<p>For ' . $all_data[0]['first name'] . ' ' . $all_data[0]['last name'] . ', we have the following race data: </p>';
									echo '<table><tr><th>Date</th><th>Location</th><th>Distance</th><th>Time</th><th>Pace</th></tr>';
									foreach ($all_data as $all_datum) {
										if (substr($all_datum['date'],5,2)>='09' && substr($all_datum['date'],5,2)<='12') $series=substr($all_datum['date'],0,4);
										else $series=substr($all_datum['date'],0,4)-1;
										echo '<tr><td><a href="/results/?date=';
										echo $all_datum['date'].'&series='.$series.'">'.$all_datum['date'].'</a></td><td>'.$all_datum['location'].'</td>';
										echo '<td>'.$all_datum['distance'].' km</td><td>'.$all_datum['time'].'</td>';
										echo '<td>'.$all_datum['pace'].'/mi</td></tr>';
										$last_date_run = $all_datum['date'];
									}
									echo '</table>';
									$beginning_of_last_series = ($current_series-1).'-10-01';
									if ($last_date_run < $beginning_of_last_series) {
										echo '<p>Since we do not have data for you from the last two Tortoise & Hare seasons, we cannot accurately predict your time.</p>';
										echo '<p>Try using the <a href="http://www.mcmillanrunning.com/calculator">McMillian Running Calculator</a> instead.</p>';
									}
									else ask_for_distance();
								}
							}
							
							function get_data_last2years() {
								global $data_last2years, $current_series;
								for ($year=$current_series-1; $year<=$current_series; $year++) {
									if ($year==$current_series-1) { $data_last2years=get_data($year); }
									else {
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
							
							
							function output_pred_time() {
								global $first_name, $last_name, $data_last2years, $distance, $distance_in_km, $current_series;
								$num_previous_year_data = 0;
								$num_current_year_data = 0;
								$previous_year_data=get_data($current_series-1);
									foreach ($previous_year_data as $previous_year_datum) {
										$num_previous_year_data++;
									}
								$current_year_data=get_data($current_series);
									if($current_year_data){
										foreach ($current_year_data as $current_year_datum) {
											$num_current_year_data++;
										}
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
							
							// BEGIN MAIN PROGRAM
							if ($last_name=='') { 
								introduction();
								ask_for_name(); 
							}
							else {
								get_data_from_all_series();
								if ($distance==NULL) {
									print_data(); // will ask_for_distance() if name in DB
								}
								else {
									output_pred_time();
								}
							}
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>