<?php
/*
Template Name: Results
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
							// CONSTANTS & VARIABLES:
							define('MYSQL_HOST', 'localhost');
							define('MYSQL_USER', 'fcrc_wp');
							define('MYSQL_PASS', 'SgfmcjP077');
							define('MYSQL_DB', 'fcrc_tortoise_hare');
							define('MYSQL_DB2', 'fcrc_membership');
							define('FIRST_SERIES', '2011');

							function list_volunteers($date) {
									echo '<h2>Volunteers</h2>';
									echo '<p>Thanks to the following volunteers at this event! They will receive 5 points for the race in the series standings if they did not get a chance to run.</p>';
									// CONNECT TO MYSQL DATABASE:
									$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB2);
									if (mysqli_connect_error()) {
										echo "<p><i>Error: Unable to connect to database.<br />";
										echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
									}
									// GET ALL DATA FROM MYSQL DATABASE:
									$all_data = mysqli_query($link,'SELECT * FROM `volunteers` WHERE `date`="'.$date.'" ORDER BY `first name` ASC');
									$num_data = 0;
									while ($row = mysqli_fetch_array($all_data)) {
										$data[$num_data] = $row;		
										$num_data++;
									}
									mysqli_free_result($all_data);
									// ELIMINATE SPACES BEFORE AND AFTER NAMES
									for ($j=0; $j<$num_data; $j++) {
										$data[$j]['first name'] = trim($data[$j]['first name']);
										$data[$j]['last name'] = trim($data[$j]['last name']);
									}
									if ($data[0]['first name'] != '') {
										echo '<ul>';
										for ($j=0; $j<$num_data; $j++) {
											echo '<li>'.$data[$j]['first name'] . ' ' . $data[$j]['last name'] . '</li>';
										}
										echo '</ul>';
									}
									else echo 'Volunteers for this race have not been entered. The Race Director or Administrative Coordinator can input volunteers <a href="http://fortcollinsrunningclub.org/input-volunteers/">here</a>.';
									mysqli_close($link); 
							}
							
							/* DETERMINE CURRENT SERIES */
								define('NEW_SEASON_START_DATE_M_D', '09-01'); /* First T&H race has traditionally been in October, but defining start of season as September 1 */
								$today = date("m-d");
								if ($today >= NEW_SEASON_START_DATE_M_D) { $current_series = date("Y"); }
								else { $current_series = date("Y")-1; }
							/* end DETERMINE CURRENT SERIES */

							$series = $_GET['series'];
							if ($series == NULL) {
								$series = $current_series; 
							}
							$order_by = $_GET['order_by'];
							if ($order_by == NULL) {
								$order_by = 'place'; 
							}
							elseif ($order_by == firstname) {
								$order_by = 'first name'; 
							}
							elseif ($order_by == lastname) {
								$order_by = 'last name'; 
							}
							$date = $_GET['date'];


							// CONNECT TO MYSQL DATABASE:
							$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
							if (mysqli_connect_error()) {
								echo "<p><i>Error: Unable to connect to database.<br />";
								echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
							}
							// FOR LISTING STANDINGS AND WHAT RACES ARE IN DATABASE
							if ($date == NULL) {
								// GET ALL DATA FROM MYSQL DATABASE:
								$all_data = mysqli_query($link,'SELECT * FROM `' .$series. '` ORDER BY "date" ASC');
								$num_data = 0;
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
								// ELIMINATE SPACES BEFORE AND AFTER NAMES
								for ($j=0; $j<$num_data; $j++) {
									$data[$j]['first name'] = trim($data[$j]['first name']);
									$data[$j]['last name'] = trim($data[$j]['last name']);
								}
								
								// DETERMINE & OUTPUT RACES IN DATABASE:
								$num_races = 0;
								if ($data) {
									foreach ($data as $datum) {
										if ($num_races == 0) { 
											$races[0]['location'] = $datum['location']; 
											$races[0]['distance'] = $datum['distance'];
											$races[0]['date'] = $datum['date'];
											$num_races++;
										}
										else { 
											foreach ($races as $race) {
												if ($datum['date'] == $race['date']) { $in_races_array = TRUE; break; }
												else {$in_races_array = FALSE;}
											}
											if ( !$in_races_array ) { 
												$races[$num_races]['location'] = $datum['location']; 
												$races[$num_races]['distance'] = $datum['distance']; 
												$races[$num_races]['date'] = $datum['date'];
												$num_races++;
											}
										}
									}
								}
								mysqli_close($link);


								
								// OUTPUT WHAT RACES WE HAVE RESULTS FOR:
								echo '<p>';
								for ($y=$current_series;$y>=FIRST_SERIES;$y--) {
									echo '[<a href="http://fortcollinsrunningclub.org/results/?series='.$y.'">'.$y.'</a>] ';
								}
								echo '</p><p>We have results for the following races in the '.$series.'-'.($series+1).' <a href="http://fortcollinsrunningclub.org/tortoise-hare/">Tortoise & Hare</a> series:</p><ol>';
								$num_races_in_db=0;
								if ($races) {
									foreach ($races as $race) {
										echo '<li><a href="http://fortcollinsrunningclub.org/results/?date=' .$race['date']. '&series='.$series.'">';
										echo $race['location'] . ' ' .$race['distance']. 'K ' . '</a>, ' .$race['date']. '</li>';
										$num_races_in_db++;
									}
								}
								if ($num_races_in_db==0) {echo 'No races yet.';} 
								echo '</ol>';
								echo '<p>Tip: use the <a href="http://fortcollinsrunningclub.org/race-calculator/">Race Calculator</a> ';
								echo 'to list your past race times or to predict your time for a future race.</p>';
								
								// OUTPUT SERIES STANDINGS
								$standings[0]['first name'] = $data[0]['first name'];
								$standings[0]['last name'] = $data[0]['last name'];	
								if ($races) {
									foreach ($races as $key => $array) {
										if ($data[0]['date'] == $array['date']) {
											$race_num = 'race '.($key+1);
											$standings[0]['races'][$race_num] = $data[0]['points'];
											break;
										}
									}
								}
								$num_standings = 1;
								
								if ($data) {
									foreach ($data as $datum) {
										for ($standings_index=0; $standings_index<$num_standings; $standings_index++) {
											if ($datum['first name'] == $standings[$standings_index]['first name'] && $datum['last name'] == $standings[$standings_index]['last name']) {
												$is_in_standings = TRUE;
												foreach ($races as $key => $array) {
													if ($datum['date'] == $array['date']) {
														$race_num = 'race '.($key+1);
														$standings[$standings_index]['races'][$race_num] = $datum['points'];
														break;
													}
												}
												break;
											}
											else {$is_in_standings = FALSE;}
										}
										if (!$is_in_standings) {
											// create $standings array that contains arrays of first name, last name, and
											//   array of points from races
											$standings[$num_standings]['first name'] = $datum['first name'];
											$standings[$num_standings]['last name'] = $datum['last name'];	
											foreach ($races as $key => $array) {
												if ($datum['date'] == $array['date']) {
													$race_num = 'race '.($key+1);
														$standings[$num_standings]['races'][$race_num] = $datum['points'];
														break;
												}

											}
											$num_standings++;
										}
									}
								}

			
								// ** ADD POINTS FOR VOLUNTEERS **
								$link2 = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB2);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$all_volunteer_data = mysqli_query($link2,'SELECT * FROM `volunteers` WHERE `date` >= "'.$series.'-09-01" AND `date` <= "'.($series+1).'-4-30"'); //selects from September-April for that series only
								$num_volunteers = 0;
								while ($row2 = mysqli_fetch_array($all_volunteer_data)) {
									$volunteers[$num_volunteers] = $row2;		
									$num_volunteers++;
								}
								mysqli_free_result($all_volunteer_data);
								mysqli_close($link2); 
								if ($num_volunteers > 0) {
									// eliminate spaces before and after names
									for ($j=0; $j<$num_volunteers; $j++) {
										$volunteers[$j]['first name'] = trim($volunteers[$j]['first name']);
										$volunteers[$j]['last name'] = trim($volunteers[$j]['last name']);
									}
									foreach ($volunteers as $volunteer) {
										for ($standings_index=0; $standings_index<$num_standings; $standings_index++) {
											if ($volunteer['first name'] == $standings[$standings_index]['first name'] && $volunteer['last name'] == $standings[$standings_index]['last name']) {
												$is_in_standings = TRUE;
												foreach ($races as $key => $array) {
													if ($volunteer['date'] == $array['date']) {
														$race_num = 'race '.($key+1);
														if (!$standings[$standings_index]['races'][$race_num]) {
															//give the volunteer 5 points
															$standings[$standings_index]['races'][$race_num] = 5;
														}
														// otherwise leave his/her points alone for that race
														break;
													}
												}
												break;
											}
											else {$is_in_standings = FALSE;}
										}
										if (!$is_in_standings) {
											// create $standings array that contains arrays of first name, last name, and
											//   array of points from races
											$standings[$num_standings]['first name'] = $volunteer['first name'];
											$standings[$num_standings]['last name'] = $volunteer['last name'];	
											foreach ($races as $key => $array) {
												if ($volunteer['date'] == $array['date']) {
													$race_num = 'race '.($key+1);
													//give the volunteer 5 points
													$standings[$num_standings]['races'][$race_num] = 5;
													break;
												}

											}
											$num_standings++;
										}
									}									
								}
														
								
								
								
								

						
							
								// create copy of $standings into $sorted_points_array that has each persons points
								// in increasing order
								$sorted_points_array = $standings;
								if (is_array($sorted_points_array[0]['races'])) {
									for ($k=0; $k<$num_standings; $k++) {
										arsort($sorted_points_array[$k]['races']);
									}
								
									// calculate points for each person for first 5 races, and plug into $standings array
									for ($j=0; $j<$num_standings; $j++) {
										$races_counted = 0;
										foreach ($sorted_points_array[$j]['races'] as $race_points) {
											if ($races_counted < 5) {
												$standings[$j]['points'] = $standings[$j]['points'] + $race_points;
												$races_counted++;
											}
											else break;
										}
									}
								}

			
								
								function compare_points_and_name($a, $b) {
									$retval = strnatcmp($b['points'], $a['points']);
									if(!$retval) return strnatcmp($a['first name'], $b['first name']);
									return $retval;
								}

								
								
								usort($standings, 'compare_points_and_name');
								//	$standings = array_reverse($standings);

							
							
								echo '<h2>Standings</h2>';
								echo '<p>The points totals for all participants of the '.$series.'-'.($series+1).' Tortoise & Hare series ';
								echo 'are listed in the table below. Note that each runner\'s best five finishes ';
								echo 'during the series are counted in the points standings per the ';
								echo '<a href="http://fortcollinsrunningclub.org/tortoise-hare/">T&H rule page</a>.</p>';
								if ($series>=2016) {
									echo '<p>Starting with the 2016-2017 series, volunteers for a race received 5 points if they did not run in it.</p>';
								}
								echo '<p>We had ' .($num_standings-1). ' runners take part in the '.$series.'-'.($series+1).' T&H series!</p>';
								echo '<table><tr>';
								echo '<th>First Name</th>';
								echo '<th>Last Name</th>';
								for ($race_num=1; $race_num<=($num_races); $race_num++) {
									echo '<th>Race ' .$race_num. '</th>';
								}
								echo '<th>TOTAL (Best5)</th></tr>';
								foreach ($standings as $standing) {
									echo '<tr><td>' .$standing['first name']. '</td>';
									echo '<td>' .$standing['last name']. '</td>';
									for ($i=1; $i<=$num_races; $i++) {
										echo '<td>';
										if ($standing['races']['race '.$i]) echo $standing['races']['race '.$i];
										else echo '-';
										echo '</td>';
									}
									echo '<td>' .$standing['points']. '</td></tr>';
								}
								echo '</table>';

							}

							// FOR LISTING SPECIFIC RESULTS ONLY
							else {
								// GET DATA FROM MYSQL DATABASE LIMITED TO SPECIFIED DATE:
								if ($order_by == 'points') { $asc_or_desc = 'DESC'; } else { $asc_or_desc = 'ASC'; }
								$all_data = mysqli_query($link,'SELECT * FROM `' .$series. '` WHERE date="' .$date. '" ORDER BY `' .$order_by. '` ' .$asc_or_desc);
								$num_data = 0;
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
								// RESULTS OUTPUT FOR SPECIFIC RACE:
								
								if ($data[0]['date'] != $date) { 
									echo 'No race results for that date or it is not part of the specified series. ';
									echo 'Please enter another date & series in the URL.'; 
								}
								else {
									echo '<table>';
									echo '<h2>' .$data[0]['location']. ' ' .$data[0]['distance']. 'K, ' .$data[0]['date'] . '</h2>';
									echo '<p>Click on one of the table headings below to sort by that criteria.</p>';
									echo '<tr><th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=place">Place</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=firstname">First Name</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=lastname">Last Name</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=predicted">Predicted</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=time">Actual</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=pace">Min./Mi.</a></th>';
									echo '<th><a href="http://fortcollinsrunningclub.org/results/?date=' .$date. '&series=' .$series. '&order_by=points">Points</a></th></tr>';
									foreach ($data as $datum) {
									echo '<tr><td>' .$datum['place']. '</td><td>' .$datum['first name']. '</td><td>' .$datum['last name']. '</td><td>' .$datum['predicted']. '</td><td>' .$datum['time']. '</td><td>' .$datum['pace']. '</td><td>' .$datum['points']. '</td></tr>';
									}
									echo '</table>';
									if ($series>='2016') list_volunteers($date); 
									echo '<p><a href="http://fortcollinsrunningclub.org/results/?series='.$series.'">Series standings</a></p>';
								}
							}
						?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>