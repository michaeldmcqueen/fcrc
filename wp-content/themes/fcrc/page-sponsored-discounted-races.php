<?php
/*
Template Name: Sponsored & Discounted Races
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
							define('MYSQL_USER', 'fcrc_wp');
							define('MYSQL_HOST', 'localhost');
							define('MYSQL_PASS', 'SgfmcjP077'); // password for MySQL database
							define('MYSQL_DB', 'fcrc_membership');
							if(! mysql_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS) ) { die('Failed to connect to host "' . MYSQL_HOST . '".'); }
							// else { echo 'Connected to MySQL server ' . MYSQL_HOST . ' as user ' . MYSQL_USER . '<br>'; }
							mysql_select_db(MYSQL_DB);
							$query = 'SELECT * FROM `discounts` ORDER BY `date` ASC';
							//echo $query.'<br />';
							$num_races=0;
							$today = date("Y-m-d");
							$all_data = mysql_query($query);
							while ($row = mysql_fetch_array($all_data)) {
								$races[$num_races] = $row;		
								$num_races++;
							}
							echo '<p>The following races are sponsored, but not organized, by the Fort Collins Running Club through its <a href="http://fortcollinsrunningclub.org/race-sponsorships/" title="FCRC Event Sponshorship program">Event Sponsorships</a> program. </p>';
							echo '<p><strong>FCRC members received discounts to all these races</strong>. Discount codes are usually available a couple months in advance. FCRC members can instantly unlock the discount codes where noted below.</p>';
							
							echo '<p>[<a href="http://fortcollinsrunningclub.org/archive-of-sponsored-discounted-races/">Archive of sponsored & discounted races</a>]';
							echo '<ol>';
							foreach ($races as $race) {
									if ($race[fcrc_sponsored] && $race['date']>=$today) {
											echo '<li>'.$race['date'].': <a href="'.$race['url'].'">'.$race['race_name'].'</a> (Unlock <a href="http://fortcollinsrunningclub.org/unlock-discount-code/?race_id='.$race['id'].'">$'.$race['discount_amount'].' discount code</a>)</li>';
									}
							}
							echo '</ol>';
							
							// FCRC'S OWN RACES
							echo '<h2>FCRC\'s Own Races</h2>';
							echo '<p>Of course, FCRC members also get discounts on the FCRC\'s own <a href="http://fortcollinsrunningclub.org/our-marquee-races/" title="FCRC\'s Marquee Races">marquee races</a>! These are races organized by the FCRC.';
							echo '<ol>';
							echo '<li>All <a href="http://fortcollinsrunningclub.org/tortoise-hare/">Tortoise & Hare</a> races are free for members (no discount code required)</li>';
							echo '<li>April: <a href="http://horsetooth-half.com">Horsetooth Half Marathon</a>: Current FCRC members can <a href="mailto:racedirector@horsetooth-half.com">email the race director</a> for a custom discount code.</li>';
							foreach ($races as $race) {
									if ($race['race_name']=='Firekracker 5k' && $race['date']>=$today) $has_firekracker_5k_discount_code=TRUE; 
							}
							if ($has_firekracker_5k_discount_code) echo '<li>'.$race['date'].': <a href="'.$race['url'].'">'.$race['race_name'].'</a> (Unlock <a href="http://fortcollinsrunningclub.org/unlock-discount-code/?race_id='.$race['id'].'"> $'.$race['discount_amount'].' discount code</a>)</li>';
							else echo '<li>July 4: <a href="http://firekracker5k.com">Firekracker 5k</a></li>'; 
							echo '</ol>';
									
							
							//UNSPONSORED RACES THAT GIVE FCRC DISCOUNTS
							foreach ($races as $race) {
								if (!$race[fcrc_sponsored] && !$race[fcrc_own_race]  && $race['date']>=$today) {
										$there_are_unsponsored_races_to_list = TRUE;
										break;
								}
							}
							echo '<h2>Unsponsored Races that Give FCRC Discounts</h2>';
							if ($there_are_unsponsored_races_to_list) {
								echo '<p>The following races are not sponsored by the FCRC but generously offers FCRC members a discount.';
								echo '<ol>';
								foreach ($races as $race) {
										if (!$race[fcrc_sponsored] && !$race[fcrc_own_race]  && $race['date']>=$today) {
												echo '<li>'.$race['date'].': <a href="'.$race['url'].'">'.$race['race_name'].'</a> (Unlock <a href="http://fortcollinsrunningclub.org/unlock-discount-code/?race_id='.$race['id'].'">$'.$race['discount_amount'].' discount code</a>)</li>';
										}
								}
								echo '</ol>';
							}
							else echo '<p>There are no unsponsored races with FCRC discounts at the moment.</p>'; 
							echo '<p>Admin: [<a href="http://fortcollinsrunningclub.org/add-discounted-race/" target="_blank">Add discounted race</a>] [<a href="https://secure192.servconfig.com:2083/cpsess6462002097/3rdparty/phpMyAdmin/sql.php?db=fcrc_membership&table=discounts&pos=0" target="_blank">Edit database</a>]';
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>