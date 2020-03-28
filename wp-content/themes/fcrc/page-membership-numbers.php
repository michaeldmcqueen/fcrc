<?php
/*
Template Name: Membership Numbers
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
							$query = 'SELECT * FROM `membership_count` ORDER BY `date` DESC';
							//echo $query.'<br />';
							$num_entries=0;
							$current_year = date("Y");
							$all_data = mysql_query($query);
							while ($row = mysql_fetch_array($all_data)) {
								$membership_counts[$num_entries] = $row;		
								$num_entries++;
							}
							$query2 = 'SELECT * FROM `members` ORDER BY `first name` ASC';
							//echo $query2.'<br />';
							$today_num_members=0;
							$all_data2 = mysql_query($query2);
							while ($row = mysql_fetch_array($all_data2)) {
								$today_roster[$today_num_members] = $row;		
								$today_num_members++;
							}
							$today = date("Y-m-d");
							$today_expired_members = 0;
							foreach ($today_roster as $today_member) {
								if ($today_member['expiration']<$today && strpos($today_member['board_member'],'y')===FALSE) { $today_expired_members++; }
							}
							$today_active = $today_num_members - $today_expired_members;
							echo '<p>The Fort Collins Running Club was formed '.($current_year-1971).' years ago in 1971.  In recent years, its membership base has grown a lot! As of this instant, there are <strong>'.$today_active.'</strong> runners with active memberships.</p><p>Below is a log of how many active members we have had since April 2014. You can check on your membership status using this <a href="http://fortcollinsrunningclub.org/membership-lookup/" title="FCRC Membership Lookup">online tool</a>.</p>';
							echo '<table><tr><th>Date</th><th>Number of Active Members</th></tr>';
							foreach ($membership_counts as $membership_count) {
								echo '<tr><td>'.$membership_count['date'].'</td><td>'.$membership_count[active].'</td>';
							}
							echo '</table>';
							
					    ?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>