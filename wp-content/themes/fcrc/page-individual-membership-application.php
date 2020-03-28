<?php
/*
Template Name: Individual Membership Application
*/

putenv('TZ=US/Mountain'); 
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
							$password = $_POST['password'];
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");

							function password_error() {
								echo '<p>Incorrect street number entered for security purposes.</p>';
								echo '<p>If you had moved, try entering your former street number. Please press the BACK button on your browser and try again.</p>';
							}

							function ask_for_name() {
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
//								echo '<p>Street number (for security purposes): <input type="text" name="password" value="" size="15" maxlength="15" /></p>';
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
								include '/home/fcrc/public_html/scripts/function-alias.php';
								$alias = alias($first_name);
								if (!$alias) {
									$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"'; 
								}
								else {
									$query = 'SELECT * FROM `members` WHERE (`first name` LIKE "%' . $first_name . '%" OR `first name` LIKE "%'. $alias . '%") AND `last name` LIKE "%' . $last_name . '%"';
								}
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
							

						
							function street_number_matches($street_number_to_check) {
								global $data;
								return (strpos($data[0]['street'], $street_number_to_check)!==FALSE);
							}

							function show_waiver_and_payment_button() {
								global $first_name, $last_name; ?>
				<p>We do not have a <?= $first_name ?> <?= $last_name ?> in our database. If this is because you were not an FCRC member recently, <strong>please continue with this application.</strong> Otherwise, please check how your name is spelled (nicknames, changed last names, and special characters such as ' and spaces can give our scripts problems), or <a href="mailto:fcrc@felixwong.com">email Felix</a> the webmaster.</p>
				<p>To apply online, agree to the following waiver and pay the membership fee with your via the Buy Now button below.  The Buy Now button takes you to a PayPal page where you can pay with a debit card, credit card, or PayPal account. </p>
				<h3>Waiver</h3>
				<p><em>I agree that I am a member of the Fort Collins Running Club and willing participant in their organized activities. I know that running in and volunteering for organized group runs, social events, and races with this club are potentially hazardous activities, which could cause injury or death. I will not participate in any club organized events, group training runs or social events, unless I am medically able and property trained to do so, and by my signature, I certify that I am medically able to perform all activities associated with the club and am in good health and properly trained. I agree to abide by all rules, policies and guidelines established by the club, including the right of any club official to deny or suspend my participation for any reason whatsoever. I attest that I have read the rules of the club and agree to abide by them. By signing this waiver, I agree to follow the club's member code of conduct as well. I assume all risks associated with being a member of this club and participating in club activities which may include: falls, contact with other participants, the effects of the weather, including high heat and/or humidity, traffic and the conditions of the road, track, or trails, all such risks being known and appreciated by me.</p>
				<p>Having read this waiver and knowing these facts and in consideration of your accepting my membership, I, for myself and anyone entitled to act on my behalf, waive and release the Fort Collins Running Club, the City of Fort Collins, and the Road Runners Club of America, all club sponsors, their representatives and successors from all claims or liabilities of any kind arising out of my participation with the club, even though that liability may arise out of negligence or carelessness on the part of the persons named in this waiver. I grant permission to all of the foregoing to use my photographs, motion pictures, recordings or any other record for any legitimate promotional purposes for the club.</em></p>
				<h3>Pay with a debit card, credit card, or PayPal</h3>
				<p>By clicking Buy Now button, you agree to the waiver above. The moment you submit online payment, you will be an active member on our roster. <em>The contact information (address, email) you enter in PayPal will the contact information we store in our membership database.  The email PayPal sends you afterward will be your receipt. The FCRC does not receive or store any credit card or bank account information from PayPal.</em></p>
				<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="DBT66FD5UTJK4">
					<input type="hidden" name="on0" value="Duration">Purchase individual membership: <br /><select name="os0">
						<option value="1 Year">1 Year $25.00 USD</option>
						<option value="2 Years">2 Years $50.00 USD</option>
						<option value="10 Years">10 Years $250.00 USD</option>
					</select><br /><br />
					<input type="hidden" name="currency_code" value="USD">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form></p>	
				<h3>Verifying your membership status</h3>
				<p>After you submit online payment, you can immediately check your membership status with this <a href="http://fortcollinsrunningclub.org/membership-lookup/">Membership Lookup Tool</a>.</p>
				<h3>Alternative paper application</h3>
				<p>If you cannot pay online with a debit card, credit card, or PayPal, you can <a href="http://fortcollinsrunningclub.org/flyers/fcrc_membership_application.docx">download a paper application</a> and mail it with a check to the address on it.  However, that would be more time consuming for you and would incur a delay due to transit time and manual processing by our membership coordinator. <em>We strongly encourage you to apply using the Buy Now button above. The FCRC never receives or stores credit card numbers or bank account information, and the transaction is thus secure.</em></p>
				<h3>Questions?</h3>
				Feel free to <a href="mailto://fortcollinsrunningclub@gmail.com">email us</a>!
<?php						}

						function suggest_family_membership($data) {
							echo '<p>We have you listed as having a <strong>family membership</strong>. Members of the family include: <ul>'; 
							$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
							if (mysqli_connect_error()) {
								echo "<p><i>Error: Unable to connect to database.<br />";
								echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
							}
							$query = 'SELECT * FROM `members` WHERE `family_head` LIKE "%' . $data[0][family_head] . '%"';
							$num_family_members=0;
							$family_data = mysqli_query($link,$query);
							while ($row = mysqli_fetch_array($family_data)) {
								$family_members[$num_family_members] = $row;		
								$num_family_members++;
							}
							mysqli_free_result($family_data);
							mysqli_close($link);
							foreach ($family_members as $family_member) { echo '<li>'.$family_member['first name'].' '.$family_member['last name'].'</li>'; }
							echo '</ul><p>If you wish to renew, please do a <a href="http://fortcollinsrunningclub.org/family-membership-application/">family membership application</a> instead.</p>';
							echo '<p>Or if you would like to switch to an individual membership, or change any of the family members above, please <a href="mailto://fortcollinsrunningclub@gmail.com">email us</a> and we will make the changes manually.</p>';
						}

						function show_renewal_instructions() { ?>
				<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="DBT66FD5UTJK4">
					<input type="hidden" name="on0" value="Duration">Renew individual membership: <br />
					<select name="os0">
						<option value="1 Year">1 Year $25.00 USD</option>
						<option value="2 Years">2 Years $50.00 USD</option>
						<option value="10 Years">10 Years $250.00 USD</option>
					</select><br /><br />
					<input type="hidden" name="currency_code" value="USD">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form></p>
	
				
				<p>If you'd rather send in a check, <a href="http://fortcollinsrunningclub.org/flyers/fcrc_membership_application.pdf">download a paper application</a> and mail it in. <em>This incurs a processing delay and more work for both you and the club, so we strongly encourage renewing and paying online by clicking the Buy Now button above. Paying online results in an immediate, automated update of your membership.</em></p>
				<p>You may also wish to check the information we have for you on file.  To do so, please enter the following information:</p>
				<form action="http://fortcollinsrunningclub.org/membership-lookup/" method="post" />
				<input type="hidden" name="first_name" value="<?= $first_name ?>" />
				<input type="hidden" name="last_name" value="<?= $last_name ?>" />
				<p>Your street number (for security purposes): <input type="text" name="password" value="" size="15" maxlength="15" /></p>
				<input type="submit" name="Submit" value=" Submit " />						
<?php					}

						
						
							// BEGIN MAIN PROGRAM

							if ($last_name=='') { /* get name and check if in database already */
								echo '<p>First, enter your name. We will then check to see if you are already in our database.</p> ';
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}
							else {
								get_data();
								if (!name_is_in_db()) { /* person not in database; must be a new member */ 
									show_waiver_and_payment_button();
								}
								else { /* member already is in database */
									echo '<p>We already have a <em>'.$data[0]['first name'].' '. $data[0]['last name'].'</em> in our database.</p>';
									$today = date("Y-m-d");
									if ($data[0][family_head]!='') { /* person has a family membership; suggest to renew family membership instead of individual */
										suggest_family_membership($data);
									}
									else { /* individual membership is appropriate */
										if ($today < $data[0]['expiration'] ) { /* membership hasn't expired yet but tells how to renew if member wants to */
											if (strpos(strtolower($data[0]['board_member']),'y') !== FALSE) { /* case: person is a board member */
												echo '<p>Your membership expires on '.$data[0]['expiration'].'. However, you are a board member. As long as you remain one, <strong>you will not need to renew it</strong>.</p>'; 	
											}
											else {
												echo '<p><strong>Your membership expires on '.$data[0]['expiration'].'.</strong> If your membership is expiring soon, you may want to renew it now.</p>';
												show_renewal_instructions();
											}
										}
										else { /* membership already expired */
											if (strpos(strtolower($data[0]['board_member']),'y') !== FALSE) { /* case: membership expired but person is a board member */
												echo '<p>Your membership expired on '.$data[0]['expiration'].'. However, <strong>because you are currently a board member, your membership is in good standing</strong>. But if you are leaving the board and your membership is expiring soon, you may want to renew it now.</p>'; 										
												show_renewal_instructions();
											}
											else { /* case: membership expired and needs to renew */ 
												echo '<p><strong>Your membership expired on '.$data[0]['expiration'].'. Please renew it now!</strong></p>';
												show_renewal_instructions();
											}
										}
									}
								}
							}
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>