<?php
/*
Template Name: Membership Lookup
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
							define('MYSQL_HOST', '127.0.0.1:32774');
							define('MYSQL_USER', 'root');
							define('MYSQL_PASS', 'root');
							define('MYSQL_DB', 'newdb');
							if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
								else { $first_name = $_POST['first_name']; } 
							if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
								else { $last_name = $_POST['last_name']; } 
							$password = $_POST['password'];
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");

							function introduction() {
								echo '<p>Use this tool to check your membership status of the FCRC, the contact information we have for you on file, and your membership expiration date.</p> ';
							}

							function password_error() {
								echo '<p>Incorrect street or PO Box number entered for security purposes.</p>';
								echo '<p>If you had moved, try entering your former street number. Example: <strong>1234</strong>, NOT 1234 Main St.</p><p>Please press the BACK button on your browser and try again.</p>';
							}

							function ask_for_name() {
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Street or PO Box number (for security purposes): <input type="text" name="password" value="" size="15" maxlength="15" />(<em>Example: <strong>1234</strong>, NOT 1234 Main St. or PO Box 1234</em>)</p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}
							

							
							function get_data() {
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								global $first_name, $last_name, $data;

								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								include '/function-alias.php';
								$alias = alias($first_name);
								if (!$alias) {
									$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"'; 
								}
								else {
									$query = 'SELECT * FROM `members` WHERE (`first name` LIKE "%' . $first_name . '%" OR `first name` LIKE "%'. $alias . '%") AND `last name` LIKE "%' . $last_name . '%"';
								}
								//echo '$query is ' . $query . '<br />';
								$num_data=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
								mysqli_close($link);
							}
							
							function name_is_in_db() {
								global $data;
								return ($data != NULL);
							}
							
							function name_not_found_error() {
								global $data, $first_name, $last_name;
								echo '<p>Sorry, no one of the name "' . $first_name . ' ' . $last_name;
								echo '" could be found in our database.</p>';
								echo '<p>Possible reasons:';
								echo '<ul><li>We might have you under a different first name in the database (e.g., William, not Bill).</li>';
								echo '<li>You might not be an FCRC member.</li>';
								echo '<li>Your name has a \' or a space and is stored without them in our database. If so, try entering your name without those characters.</li>';
								echo '</ul><p>You can <a href="mailto:membership@fortcollinsrunningclub.org">e-mail us</a> to inquire about your membership status. Or if you <em>know</em> you are not a member, <a href="http://fortcollinsrunningclub.org/join-the-fcrc/">join the FCRC</a> today.</p><p>Or you could try again.</p>';
								ask_for_name();
							}
							
							function street_number_matches($street_number_to_check) {
								global $data;
								return (strpos(strtoupper($data[0]['street']), $street_number_to_check)!==FALSE);
							}
							

							function print_data() {
								global $data, $first_name, $last_name;
								echo '<p>First name: ' . $data[0]['first name'];
								echo '<br />Last name: ' . $data[0]['last name'];
								echo '<br />Board member: ';
								$board_member = (strpos(strtolower($data[0]['board_member']),'y') !== FALSE);
								if ($board_member) { echo 'yes'; }
								else { echo 'no'; }
								echo '<br />Unsubscribe to newsletter: ';
								$unsubscribe = (strpos(strtolower($data[0]['unsubscribe']),'y') !== FALSE);
								if ($unsubscribe) { echo 'yes'; }
								else { echo 'no (Note: only active members can receive newsletter)'; }
								echo '<br />Address: ';
								echo $data[0]['street'] . ', ' . $data[0]['city'] . ', ' . $data[0]['state'] . ', ' . $data[0]['zip'];
								echo '<br />Phone: ' . $data[0]['phone'];
								echo '<br />E-mail: ' . $data[0]['email'];
								echo '<br />Membership Type: ';
									if (!$data[0]['family_head']) echo 'Individual';
									else {
										echo 'Family';
										echo '<br />Family member in charge of renewing: ' . $data[0]['family_head'];
										echo '<br />Family members: ';
										$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
										if (mysqli_connect_error()) {
											echo "<p><i>Error: Unable to connect to database.<br />";
											echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
										}
										$family_member_data = mysqli_query($link,'SELECT * FROM `members` WHERE `family_head` LIKE "' . $data[0]['family_head'] . '"');
										$num_data = 0;
										while ($row = mysqli_fetch_array($family_member_data)) {
											$family_members[$num_data] = $row;		
											$num_data++;
										}
										mysqli_free_result($family_member_data);
										mysqli_close($link);
										for ($i=0; $i<$num_data; $i++) {
											echo $family_members[$i]['first name'].' '.$family_members[$i]['last name'];
											if ($i < $num_data-1) echo ', ';
										}
									}
								echo '<br />Expiration: ' . $data[0]['expiration'] . '</p>';
								$today = date("Y-m-d");
								if ($today < $data[0]['expiration']) { echo '<p><strong>Your membership is in good standing.</strong></p><p>Or if it expiring soon, you may choose to <a href="http://fortcollinsrunningclub.org/join-the-fcrc/">renew it now</a>.</p>'; }
								elseif ($board_member) {echo '<p>Although your membership expired, because you are a board member you do not need to renew it. <strong>Your membership is in good standing.</strong></p><p>Or if it expiring soon, you may choose to <a href="http://fortcollinsrunningclub.org/join-the-fcrc/">renew it now</a>.</p>'; }
								else { echo '<p><strong>Your membership expired! Please <a href="http://fortcollinsrunningclub.org/join-the-fcrc/">renew</a> today!</strong></p>'; }
								echo '<p>If any of the information above needs to be changed, please <a href="mailto:membership@fortcollinsrunningclub.org">email us</a>.</p>';
							}
							
	
							
					
							// BEGIN MAIN PROGRAM

							if ($last_name=='') {
								introduction();
								ask_for_name(); 
							}

							else {
								get_data();
								if (!name_is_in_db()) { name_not_found_error(); }
								elseif (!street_number_matches($password)) { password_error(); }
								else { print_data(); }
							}
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>