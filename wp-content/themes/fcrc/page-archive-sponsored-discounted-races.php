<?php
/*
Template Name: Archive of Sponsored & Discounted Races
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
							echo '<p>The following races were sponsored, but not organized, by the Fort Collins Running Club through its <a href="/race-sponsorships/" title="FCRC Event Sponshorship program">Event Sponsorships</a> program. </p>';
							echo '<p><strong>FCRC members received discounts to all these races</strong>. Discount codes were usually available a couple months in advance. FCRC members could instantly unlock discount codes online. Read about the <a href="/join-the-fcrc/">other benefits</a> of joining the Fort Collins Running Club!</p>';
							echo '<p>[<a href="/races-sponsored-by-the-fcrc/">Current sponsored & discounted races</a>]';
							echo '<ol>';
							foreach ($races as $race) {
									if ($race[fcrc_sponsored] && $race['date']<$today) {
											echo '<li>'.$race['date'].': <a href="'.$race['url'].'">'.$race['race_name'].'</a></li>';
									}
							}
							echo '</ol>';
							echo '<h2>FCRC\'s Own Races</h2>';
							echo '<p>Of course, FCRC members also got discounts on the FCRC\'s own <a href="/our-marquee-races/" title="FCRC\'s Marquee Races">marquee races</a>! These are races organized by the FCRC.';
							echo '<ol>';
							echo '<li>April: <a href="http://horsetooth-half.com">Horsetooth Half Marathon</a></li>';
							echo '<li>July 4: <a href="http://firekracker5k.com">Firekracker 5k</a></li>';
							echo '</ol>';
														echo '<h2>Unsponsored Races that Gave FCRC Discounts</h2>';
							echo '<p>The following races were not sponsored by the FCRC but generously offered FCRC members a discount.';
							echo '<ol>';
							foreach ($races as $race) {
									if (!$race[fcrc_sponsored] && !$race[fcrc_own_race]  && $race['date']<$today) {
											echo '<li>'.$race['date'].': <a href="'.$race['url'].'">'.$race['race_name'].'</a></li>';
									}
							}
							echo '</ol>';
							echo '<p>Admin: [<a href="/add-discounted-race/" target="_blank">Add discounted race</a>] [<a href="https://secure192.servconfig.com:2083/cpsess6462002097/3rdparty/phpMyAdmin/sql.php?db=fcrc_membership&table=discounts&pos=0" target="_blank">Edit database</a>]</p>';
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>