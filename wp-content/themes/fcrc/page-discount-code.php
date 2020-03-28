<?php
/*
Template Name: Unlock Discount Code
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
							define('MYSQL_DB', 'fcrc_membership');
							if ($_GET['first_name'] != '') { $first_name = $_GET['first_name']; }
							else { $first_name = $_POST['first_name']; } 
							if ($_GET['last_name'] != '') { $last_name = $_GET['last_name']; }
							else { $last_name = $_POST['last_name']; } 
							if ($_GET['race_id'] != '') { $race_id = $_GET['race_id']; } 
							$password = $_POST['password'];
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");

							function introduction() {
								global $race_id;
								echo '<p>Use this tool to unlock the discount code for FCRC discounted races. <strong>Valid for FCRC members only.</strong></p>';
							}

							function no_race_error() {
								echo '<strong>Error: $race_id is undefined.</strong>';
							}
							
							function password_error() {
								echo '<p>Incorrect street number entered for security purposes.</p>';
								echo '<p>If you had moved, try entering your former street number. Please press the BACK button on your browser and try again.</p>';
							}

							function ask_for_name() {
								echo '<p>Please enter your following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Street number (for security purposes): <input type="text" name="password" value="" size="15" maxlength="15" />(<em>Example: <strong>1234</strong>, NOT 1234 Main St.</em>)</p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
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
							
							function name_is_in_db() {
								global $data;
								return ($data != NULL);
							}
							
							function name_not_found_error() {
								global $data, $first_name, $last_name;
								echo '<p>Sorry, no one of the name "' . $first_name . ' ' . $last_name;
								echo '" could be found in our database. You might not be an FCRC member.  You can <a href="mailto:fortcollinsrunningclub@gmail.com">e-mail us</a> to inquire about your membership status.</p>Or you could try again. ';
								ask_for_name();
							}
							
							
							
							function street_number_matches($street_number_to_check) {
								global $data;
								return (strpos($data[0]['street'], $street_number_to_check)!==FALSE);
							}
							

							function print_data() {
								global $data, $race_id, $first_name, $last_name;
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								$query = 'SELECT * FROM `discounts` WHERE `id`="' . $race_id . '"';
								$race_data = mysqli_query($link,$query);
								$num_data = 0;
								while ($row = mysqli_fetch_array($race_data)) {
									$discounts[$num_data] = $row;
									$num_data++;
								}
								mysqli_free_result($race_data);
								mysqli_close($link);
								echo '<p>Hi '.$data[0]['first name'].' '.$data[0]['last name'].',</p>';
								echo '<p>The discount code (worth $'.$discounts[0]['discount_amount'].') for <em>'.$discounts[0]['race_name'].' ('.$discounts[0]['date'].')</em> is <strong>'.$discounts[0][discount_code].'</strong></p>';
								$today = date("Y-m-d");
								$expiration_date = $data[0]['expiration'];
								if ($today > $expiration_date && substr($data[0]['board_member'],0)!='y') {
									echo '<p>Your membership expired on '.$expiration_date.'.  Please <a href="http://fortcollinsrunningclub.org/join-the-fcrc/">renew</a> it.';
								}
								echo '<p>Register for the race by clicking <a href="'.$discounts[0][url].'" target=_blank>here</a>. ';
								if ($discounts[0][special_instructions] != '') { echo '<em>'.$discounts[0][special_instructions].'</em>'; }
								echo '</p><p>Or return to the <a href="http://fortcollinsrunningclub.org/races-sponsored-by-the-fcrc/" title="Races Sponsored by the FCRC">Races Sponsored by the FCRC</a> page.</p>';
							}
							
	
							
					
							// BEGIN MAIN PROGRAM

							if ($last_name=='') {
								if ($race_id != '' ) {
									introduction();
									ask_for_name();
								}
								else no_race_error();
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