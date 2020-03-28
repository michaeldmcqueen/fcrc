<?php
/*
Template Name: Add Discounted Race
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
							define('PASSWORD', 'T&H*0525');   // password to allow administration to add race
							if ($_GET['password'] != '') { $password = $_GET['password']; }
							else { $password = $_POST['password']; }
							if ($_GET['function'] != '') { $function = $_GET['function']; }
							else { $function = $_POST['function']; }
							
							if ($_GET['date'] != '') { $date = $_GET['date']; }
							else { $date = $_POST['date']; }
							if ($_GET['race_name'] != '') { $race_name = $_GET['race_name']; }
							else { $race_name = $_POST['race_name']; }
							if ($_GET['fcrc_sponsored'] != '') { $password = $_GET['fcrc_sponsored']; }
							else { $fcrc_sponsored = $_POST['fcrc_sponsored']; }
							if ($_GET['fcrc_own_race'] != '') { $password = $_GET['fcrc_own_race']; }
							else { $fcrc_own_race = $_POST['fcrc_own_race']; }
							if ($_GET['url'] != '') { $password = $_GET['url']; }
							else { $url = $_POST['url']; }
							if ($_GET['discount_code'] != '') { $password = $_GET['discount_code']; }
							else { $discount_code = $_POST['discount_code']; }
							if ($_GET['discount_amount'] != '') { $password = $_GET['discount_amount']; }
							else { $discount_amount = $_POST['discount_amount']; }
							if ($_GET['special_instructions'] != '') { $password = $_GET['special_instructions']; }
							else { $special_instructions = $_POST['special_instructions']; }					
							
							function introduction() {
								echo '<p>This is a for authorized FCRC web administrators script to add sponsored and discounted races. Races added here will show up on the <a href="http://fortcollinsrunningclub.org/races-sponsored-by-the-fcrc/">Sponsored & Discounted Races</a> page.</p> ';
								echo '<form action="" method="post" />';
								echo '<p>First, please enter the administrator password: <input type="password" name="password" value="" size="15" maxlength="15" /></p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';								
							}

							function password_error() {
								echo '<p>Incorrect password.  Please press the BACK button on your browser and try again.</p>';
								echo '<p>Please note that this tool is meant for authorized FCRC administrators only and not for general FCRC members.</p>';
							}

							function ask_for_new_race_info() {
								echo '<h2>Add New Sponsored or Discounted Race</h2>';
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>Race name: <input type="text" name="race_name" value="" size="30" maxlength="30" /> (E.g., <strong>Sharin\' O\' the Green 5k</strong>)</p>';
								echo '<p>Date: <input type="text" name="date" value="" size="10" maxlength="10" /> (Must be in format <strong>yyyy-mm-dd</strong>)</p>';
								echo '<p>Registration website: <input type="text" name="url" value="http://" size="400" maxlength="100" /></p>';
								echo '<p>Discount code: <input type="text" name="discount_code" value="" size="40" maxlength="40" /></p>';
								echo '<p>Discount amount: $<input type="text" name="discount_amount" value="5" size="2" maxlength="5" /></p>';
								echo '<p>Sponsored by FCRC? (y=yes; n=no): <input type="text" name="fcrc_sponsored" value="y" size="1" maxlength="1"/></p>';
								echo '<p>FCRC\'s own race? (y=yes; n=no): <input type="text" name="fcrc_own_race" value="n" size="1" maxlength="1"/></p>';
								echo '<p>Special instructions (if any): <input type="text" name="special_instructions" value="" size="400" maxlength="100" /></p>';
								echo '<input type="hidden" name="password" value="'.PASSWORD.'" />';
								echo '<input type="hidden" name="function" value="add_race_to_db" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
								echo '</form>';
							}

						
							function add_race_to_db() {
								global $date, $race_name, $fcrc_sponsored, $fcrc_own_race, $url, $discount_code, $discount_amount, $special_instructions;
							
								// CONNECT TO MYSQL DATABASE:
								$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
								if (mysqli_connect_error()) {
									echo "<p><i>Error: Unable to connect to database.<br />";
									echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
								}
								if ($fcrc_sponsored=='y' || $fcrc_sponsored=='Y') $fcrc_sponsored=1;
								else $fcrc_sponsored=0;
								if ($fcrc_own_race=='y' || $fcrc_own_race=='Y') $fcrc_own_race=1;
								else $fcrc_own_race=0;
								$query = "INSERT INTO `discounts` (`id`, `date`, `race_name`, `fcrc_sponsored`, `fcrc_own_race`, `url`, `discount_code`, `discount_amount`, `special_instructions`) VALUES (NULL, '";
								$query = $query. $date."', '".$race_name."', '".$fcrc_sponsored."', '".$fcrc_own_race."', '".$url."', '".$discount_code."', '".$discount_amount."', '".$special_instructions."'); ";
								echo '<p>'.$query .'</p>'; 
								mysqli_query($link,$query) or die('MySQL data entry failed.'); 
								mysqli_close($link);
								echo '<p>Successfully entered data for ' .$race_name.'</p>';
								echo '<p>[<a href="http://fortcollinsrunningclub.org/add-discounted-race/">Add another race</a>] [<a href="https://secure192.servconfig.com:2083/cpsess6462002097/3rdparty/phpMyAdmin/sql.php?db=fcrc_membership&table=discounts&pos=0" target="_blank">Edit database</a>]';
							}

							
							// BEGIN MAIN PROGRAM

							if ($password=='') {
								introduction();
							}
							elseif ($password != PASSWORD) {
								password_error();
							}
							else { 
								if ($function=='') {
									echo '<p>Password entered correctly.  You are authorized to add a new sponsored or discounted race.</p>';
									ask_for_new_race_info();
								}
								if ($function=='add_race_to_db') add_race_to_db();
							}
				
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>