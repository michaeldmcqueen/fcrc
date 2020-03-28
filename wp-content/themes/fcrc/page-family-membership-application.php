<?php
/*
Template Name: Family Membership Application
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
							function title_case($str) {
								$result = "";

								$arr = array();
								$pattern = '/([;:,-.\/ X])/';
								$array = preg_split($pattern, $str, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

								foreach($array as $k => $v) 
									$result .= ucwords(strtolower($v));
								return $result;
							}

							define('MYSQL_HOST', 'localhost');
							define('MYSQL_USER', 'fcrc_wp');
							define('MYSQL_PASS', 'SgfmcjP077');
							define('MYSQL_DB', 'fcrc_membership');
							$first_name = $_POST['first_name'];
							$last_name = $_POST['last_name']; 
							$password = $_POST['password'];
							$function = $_POST['function'];
							$first_name = trim(ucwords(strtolower(stripslashes($first_name)))," ");
							$last_name = trim(ucwords(strtolower(stripslashes($last_name)))," ");
							// variables for all new family members are below
							$street = title_case($_POST['street']);
							$city = title_case($_POST['city']);
							$state = $_POST['state'];
							$zip = $_POST['zip'];
							$family_head = title_case($_POST['family_head']);

							include '/home/fcrc/public_html/scripts/function-alias.php';
							$link = mysqli_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASS,MYSQL_DB);
							if (mysqli_connect_error()) {
								echo "<p><i>Error: Unable to connect to database.<br />";
								echo "Debugging error: " . mysqli_connect_error() .  '</i></p>';
							}
				
												
							
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
								global $link,$first_name, $last_name, $data;
								// CONNECT TO MYSQL DATABASE:
								$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"';
								// echo '$query is ' . $query . '<br />';
								$num_data=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
							}
							
							function alias_is_in_db($first_name, $last_name) {
								if (alias($first_name)!='') return name_is_in_db(alias($first_name), $last_name);
								else return FALSE;
							}
							
							function name_is_in_db($first_name, $last_name) {
								global $link;
								$query = 'SELECT * FROM `members` WHERE `first name` LIKE "%' . $first_name . '%" AND `last name` LIKE "%' . $last_name . '%"'; 
								$num_data=0;
								$all_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($all_data)) {
									$data[$num_data] = $row;		
									$num_data++;
								}
								mysqli_free_result($all_data);
								return ($data != NULL);
							}
							
							function change_name_to_alias_if_necessary() {
								global $first_name, $last_name;
								if (!name_is_in_db($first_name,$last_name)) {
									if (alias($first_name)!='' && name_is_in_db(alias($first_name),$last_name)) 
										$first_name = alias($first_name);
								}
							}
						
							function street_number_matches($street_number_to_check) {
								global $data;
								return (strpos($data[0]['street'], $street_number_to_check)!==FALSE);
							}

							function list_family_members($data) {
								global $link, $num_family_members;
								$query = 'SELECT * FROM `members` WHERE `family_head` LIKE "%' . $data[0][family_head] . '%"';
								$num_family_members=0;
								$family_data = mysqli_query($link,$query);
								while ($row = mysqli_fetch_array($family_data)) {
									$family_members[$num_family_members] = $row;		
									$num_family_members++;
								}
								mysqli_free_result($family_data);
								echo 'There are <strong>'.$num_family_members.' members</strong> in your family membership, including: <ul>';
								foreach ($family_members as $family_member) { echo '<li>'.$family_member['first name'].' '.$family_member['last name'].'</li>'; }
								echo '</ul>';
							}

							function ask_for_new_family_members_info() {
								global $first_name, $last_name;
								echo '<p>We do not have a ' . $first_name . ' ' . $last_name . ' in our database. If this is because you were not an FCRC member recently, <strong>please continue with this application using one of the following two methods.</strong> Otherwise, please check how your name is spelled (nicknames, changed last names, and special characters such as \' and spaces can give our scripts problems), or <a href="mailto:fcrc@felixwong.com">email Felix</a> the webmaster.</p>';
								echo '<h2>Method 1: Paper Application</h2>';
								echo '<p>If you prefer not to pay online with a debit card, credit card, or PayPal, you can <a href="http://fortcollinsrunningclub.org/flyers/fcrc_membership_application.docx">download a paper application</a> and mail it with a check to the address on it.  However, that would be more time consuming for you and would incur a delay due to transit time and manual processing by our membership coordinator. <em>We strongly encourage you to apply using the online application below. The FCRC never receives or stores credit card numbers or bank account information, and the transaction is thus secure.</em></p>';
								echo '<h2>Method 2: Online Application (Recommended)</h2>';
								echo '<p>In this method, you enter the family members you would like to be part of your FCRC Family Membership.  After submitting, a button will appear that allows you to pay for your membership with a debit card, credit card, or PayPal.</p>';
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>Head of family: <input type="text" name="family_head" value="" size="40" maxlength="40" /> (E.g., <i>John Smith</i>)</p>';
								echo '<p>Street: <input type="text" name="street" value="" size="40" maxlength="40" /></p>';
								echo '<p>City: <input type="text" name="city" value="" size="20" maxlength="20" /></p>';
								echo '<p>State: <input type="text" name="state" value="CO" size="2" maxlength="2" /></p>';
								echo '<p>Zip: <input type="number" name="zip" value="" size="5" maxlength="5" /></p>';
								echo '<p>New family members, <strong>including Head of Family</strong>:</p>';
								echo '<table><tr><th>First name</th><th>Last name</th><th>Phone (xxx-xxx-xxxx)</th><th>E-mail</th></tr>';
								for ($h=0; $h<7; $h++) { //fields for new family members
									echo '<tr><td align="center"><input type="text" name="data['.$h.'][first_name]" value="" size="15" maxlength="15" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][last_name]" value="" size="15" maxlength="15" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][phone]" value="" size="12" maxlength="12" /></td>';
									echo '<td align="center"><input type="text" name="data['.$h.'][email]" value="" size="30" maxlength="30" /></td>';
								}
								echo '</table>';
								echo '<input type="hidden" name="function" value="add_new_family_members" />';
								echo '<input type="submit" name="Submit" value=" Submit " />';
								echo '</form>';
							}

							function add_new_family_members_to_db() {
								global $link, $family_head, $street, $city, $state, $zip, $num_family_members;
								// CONNECT TO MYSQL DATABASE:
								$today = date("Y-m-d");
								$num_family_members=0;
								echo '<p>';
								for ($i=0; $i<7; $i++) {
									if ($_POST['data'][$i]['first_name'] != '') {
										$data[$i]['first_name'] = title_case(stripslashes(trim($_POST['data'][$i]['first_name'])));
										$data[$i]['last_name'] = title_case(stripslashes(trim($_POST['data'][$i]['last_name'])));
										echo '<br /><em>Successfully entered ' .$data[$i]['first_name']. ' '.$data[$i]['last_name'].' to the FCRC database.</em> ';
										if (name_is_in_db($data[$i]['first_name'],$data[$i]['last_name']) || alias_is_in_db($data[$i]['first_name'],$data[$i]['last_name'])) {
											notify_felix($family_head,$data[$i]['first_name'],$data[$i]['last_name']);
										}
										$data[$i]['phone'] = $_POST['data'][$i]['phone'];
										$data[$i]['email'] = strtolower($_POST['data'][$i]['email']);
										$data[$i]['board_member'] = 'n';
										$data[$i]['expiration'] = $today; // since hasn't paid yet
										$query = "INSERT INTO `members` (`first name` , `last name` , `board_member` , `street`, `city`, `state`, `zip`, `phone`, `expiration`, `family_head`, `email`, `first_joined`) VALUES ('";
										$query = $query. mysqli_real_escape_string($link,$data[$i]['first_name'])."', '".mysqli_real_escape_string($link,$data[$i]['last_name'])."', '".$data[$i]['board_member']."',  '".$street."', '".$city."', '".$state."', '".$zip."', '".$data[$i]['phone']."', '".$data[$i]['expiration']."', '".$family_head."', '".$data[$i]['email']."', '".$today."')";
										mysqli_query($link,$query) or die('MySQL data entry failed.'); 
										$num_family_members++;
									}
								}
								echo '</p>';
							}
							
							function member_already_in_db($first_name, $last_name) {
								if (FALSE) {
									return TRUE;
								}
								else return FALSE;
							}

							function show_waiver_and_payment_button() {
								global $num_family_members; ?>
				<p>To pay online, agree to the following waiver and pay the membership fee with your via the Buy Now button below.  The Buy Now button takes you to a PayPal page where you can pay with a debit card, credit card, or PayPal account. </p>
				<h3>Waiver</h3>
				<p><em>I agree that I am a member of the Fort Collins Running Club and willing participant in their organized activities. I know that running in and volunteering for organized group runs, social events, and races with this club are potentially hazardous activities, which could cause injury or death. I will not participate in any club organized events, group training runs or social events, unless I am medically able and property trained to do so, and by my signature, I certify that I am medically able to perform all activities associated with the club and am in good health and properly trained. I agree to abide by all rules, policies and guidelines established by the club, including the right of any club official to deny or suspend my participation for any reason whatsoever. I attest that I have read the rules of the club and agree to abide by them. By signing this waiver, I agree to follow the club's member code of conduct as well. I assume all risks associated with being a member of this club and participating in club activities which may include: falls, contact with other participants, the effects of the weather, including high heat and/or humidity, traffic and the conditions of the road, track, or trails, all such risks being known and appreciated by me.</p>
				<p>Having read this waiver and knowing these facts and in consideration of your accepting my membership, I, for myself and anyone entitled to act on my behalf, waive and release the Fort Collins Running Club, the City of Fort Collins, and the Road Runners Club of America, all club sponsors, their representatives and successors from all claims or liabilities of any kind arising out of my participation with the club, even though that liability may arise out of negligence or carelessness on the part of the persons named in this waiver. I grant permission to all of the foregoing to use my photographs, motion pictures, recordings or any other record for any legitimate promotional purposes for the club.</em></p>
				<h3>Pay with a debit card, credit card, or PayPal</h3>
				<p>By clicking Buy Now button, you agree to the waiver above. The moment you submit online payment, you will be an active member on our roster. <em>The email PayPal sends you afterward will be your receipt. The FCRC does not receive or store any credit card or bank account information from PayPal.</em></p>
				<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick"/>
					<input type="hidden" name="hosted_button_id" value="U2KE7BBEZRXP2"/>
<?php				switch ($num_family_members) {
						case 2:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 2 membership: $35.00<input type="hidden" name="os0" value="2 family members" />';
							break;
						case 3:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 3 membership: $45.00<input type="hidden" name="os0" value="3 family members" />';
							break;
						case 4:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 4 membership: $55.00<input type="hidden" name="os0" value="4 family members" />';
							break;
						case 5:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 5 membership: $65.00<input type="hidden" name="os0" value="5 family members" />';
							break;
						case 6:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 6 membership: $75.00<input type="hidden" name="os0" value="6 family members" />';
							break;
						case 7:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 7 membership: $85.00<input type="hidden" name="os0" value="7 family members" />';
							break;
					} ?>
					<input type="hidden" name="currency_code" value="USD"/>
					<p><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/></p>
				</form></p>
				<h3>Verifying your membership status</h3>
				<p>After you submit online payment, you can immediately check your membership status with this <a href="http://fortcollinsrunningclub.org/membership-lookup/">Membership Lookup Tool</a>.</p>
				<h3>Questions?</h3>
				Feel free to <a href="mailto://fortcollinsrunningclub@gmail.com">email us</a>!
<?php						}
							
						function suggest_individual_membership() {
							echo '<p>We have you listed as having an <strong>individual membership</strong>. If you wish to renew, please do an <a href="http://fortcollinsrunningclub.org/individual-membership-application/">individual membership application</a> instead.</p>';
							echo '<p>Or if you would like to switch to a family membership, <a href="mailto://fortcollinsrunningclub@gmail.com">email us</a> and we will make the changes manually.</p>';
						}

						function show_renewal_instructions() { 
							global $num_family_members; ?>
				<p><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
					<input type="hidden" name="cmd" value="_s-xclick"/>
					<input type="hidden" name="hosted_button_id" value="U2KE7BBEZRXP2"/>
<?php				switch ($num_family_members) {
						case 2:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 2 membership: $35.00<input type="hidden" name="os0" value="2 family members" />';
							break;
						case 3:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 3 membership: $45.00<input type="hidden" name="os0" value="3 family members" />';
							break;
						case 4:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 4 membership: $55.00<input type="hidden" name="os0" value="4 family members" />';
							break;
						case 5:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 5 membership: $65.00<input type="hidden" name="os0" value="5 family members" />';
							break;
						case 6:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 6 membership: $75.00<input type="hidden" name="os0" value="6 family members" />';
							break;
						case 7:
							echo '<input type="hidden" name="on0" value="Family Size" />Renew family of 7 membership: $85.00<input type="hidden" name="os0" value="7 family members" />';
							break;
					} ?>
					<input type="hidden" name="currency_code" value="USD"/>
					<p><input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"/><img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"/></p>
				</form></p>	
				<p>If you'd rather send in a check, <a href="http://fortcollinsrunningclub.org/flyers/fcrc_membership_application.pdf">download a paper application</a> and mail it in. <em>This incurs a processing delay and more work for both you and the club, so we strongly encourage renewing and paying online by clicking the Buy Now button above. Paying online results in an immediate, automated update of your membership.</em></p>
				<p>If you need to add or remove family members, please <a href="mailto://fortcollinsrunningclub@gmail.com">email us</a> and we will make the changes manually.</p>	
				<p>You may also wish to check the information we have for you on file.  To do so, please enter the following information:</p>
				<form action="http://fortcollinsrunningclub.org/membership-lookup/" method="post" />
				<input type="hidden" name="first_name" value="<?= $first_name ?>" />
				<input type="hidden" name="last_name" value="<?= $last_name ?>" />
				<p>Your street number (for security purposes): <input type="text" name="password" value="" size="15" maxlength="15" /></p>
				<input type="submit" name="Submit" value=" Submit " />	
<?php					}

						function notify_felix($family_head, $family_member_first_name, $family_member_last_name) {
								// email Felix a message saying that member was not entered into FCRC database
								$to = 'me@felixwong.com'; 
								$subject = 'PayPal IPN: Error entering family membership headed by' . $family_member_first_name . ' ' .$family_member_last_name .  ' into FCRC database'; 
								$body = $family_member_first_name . ' ' .$family_member_last_name . ' (or family member) not entered into the database. <em>Family member(s) may already have been in database. Emailing FCRC webmaster.</em> Check email for recent PayPal submission.'; 
								if (mail($to, $subject, $body)) {   
									echo("<p>Email successfully sent!</p>");  } 
								else {   echo("<p>Email delivery failed…</p>"); } 
/*							
							// email Felix a message saying that member was not entered into FCRC database
							//Create a new PHPMailer instance
							$mail = new PHPMailer;
							//Tell PHPMailer to use SMTP
							$mail->isSMTP();
							//Enable SMTP debugging
							// 0 = off (for production use)
							// 1 = client messages
							// 2 = client and server messages
							$mail->SMTPDebug = 0;
							//Ask for HTML-friendly debug output
							$mail->Debugoutput = 'html';
							//Set the hostname of the mail server
							$mail->Host = "smtp.gmail.com";
							//Set the SMTP port number - likely to be 25, 465 or 587
							$mail->Port = 587;
							//Whether to use SMTP authentication
							$mail->SMTPAuth = true;
							//Username to use for SMTP authentication
							$mail->Username = "fortcollinsrunningclub@gmail.com";
							//Password to use for SMTP authentication
							$mail->Password = "FCRCboard15!";
							$mail->SMTPSecure  = "tls"; //Secure conection
							$mail->Priority    = 1; // Highest priority - Email priority (1 = High, 3 = Normal, 5 = low)
							//Set who the message is to be sent from
							$mail->setFrom('fortcollinsrunningclub@gmail.com', 'Fort Collins Running Club');
							//Set an alternative reply-to address
							$mail->addReplyTo('fortcollinsrunningclub@gmail.com', 'Fort Collins Running Club');
							//Set who the message is to be sent to
							$mail->addAddress('me@felixwong.com', 'Felix Wong');
							//Set the subject line
							$mail->Subject = 'page-family-membership-application.php: For ' . $family_head . ', duplicate family member '.$family_member_first_name.' '.$family_member_last_name.' detected';
							//Read an HTML message body from an external file, convert referenced images to embedded,
							//convert HTML into a basic plain-text alternative body
							$mail->msgHTML(file_get_contents('/home/fcrc/public_html/wp/wp-content/themes/twentythirteen-child/duplicate_member_detected_error.html'), dirname(__FILE__));
							//Replace the plain text body with one created manually
							$mail->AltBody = $family_member_first_name . ' ' .$famliy_member_last_name. ' is already in the database and may have been duplicated.';
							//Attach an attachment
							//$mail->addAttachment('sample_attachment.pdf');
							//send the message, check for errors
							if (!$mail->send()) {
								echo "<em>Mailer Error: " . $mail->ErrorInfo . "</em>";
							} else {
								echo "<em>Family member may already have been in database. Emailing FCRC webmaster.</em>";
							}
*/							
						}
						
						
							// BEGIN MAIN PROGRAM
							change_name_to_alias_if_necessary();
							get_data();
							if ($function=='add_new_family_members') {
								add_new_family_members_to_db();
								show_waiver_and_payment_button();
							}
							elseif ($last_name=='') {
								echo '<p>First, enter your name (or the name of someone who is part of your family membership). We will then check to see if you are already in our database.</p> ';
								echo '<p>Please enter the following information:</p>';
								echo '<form action="" method="post" />';
								echo '<p>First name: <input type="text" name="first_name" value="" size="25" maxlength="25" /></p>';
								echo '<p>Last name: <input type="text" name="last_name" value="" size="25" maxlength="25" /></p>';
								echo '<input type="submit" name="Submit" value=" Submit " />';
							}
							else {
								if (!name_is_in_db($first_name, $last_name)) { /* person not in database; must be a new member */ 
									ask_for_new_family_members_info();
								}
								else { /* member already is in database */
									echo '<p>We already have a <em>'.$first_name.' '. $last_name.'</em> in our database.</p>';
									$today = date("Y-m-d");
									if ($data[0][family_head]!='') { /* person has a family membership */
										list_family_members($data);
										if ($today < $data[0]['expiration'] ) { /* membership hasn't expired yet but tells how to renew if member wants to */
											echo '<p><strong>Your family membership expires on '.$data[0]['expiration'].'.</strong> If it is expiring soon, you may want to renew it now.</p>';
											show_renewal_instructions();
										}
										else { /* membership already expired */
											if (strpos(strtolower($data[0]['board_member']),'y') !== FALSE) { /* case: membership expired but person is a board member */
												echo '<p>Your family membership expired on '.$data[0]['expiration'].'. However, because you are currently a board member, your membership is in good standing. <strong>You do not need to renew at this time.</strong></p>'; 										
										}
											else { /* case: membership expired and needs to renew */ 
												echo '<p><strong>Your family membership expired on '.$data[0]['expiration'].'. Please renew it now!</strong></p>';
												show_renewal_instructions();
											}
										}
									}
									else { /* individual membership is appropriate */
										suggest_individual_membership();
									}
								}
							}
							mysqli_close($link);
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>