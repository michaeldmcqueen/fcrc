<?php
/*
Template Name: Runnovation Video Pitches Randomized
*/

date_default_timezone_set('America/Denver');
$today = date("Y-m-d");
get_header();


?>

		<div id="primary">
			<div id="content" role="main">

				<?php // get_template_part( 'content', 'page' ); ?>
			
				<article class="page type-page status-publish hentry p-4 content-area md:w-4/5 mx-auto">
					<header class="entry-header">
						<h1 class="entry-title text-5xl"><?php the_title(); ?></h1>

					</header><!-- .entry-header -->
					<div class="entry-content">
						<p>Below are the video pitches that were submitted for the 2017 <a href="http://fortcollinsrunningclub.org/fcrc-runnovation-prize/">FCRC Runnovation Prize</a>!<p>
						<p>The deadline for entering the competition has now passed and judging will now commence on the six video pitches below.  Per the <a href="http://fortcollinsrunningclub.org/fcrc-runnovation-prize-faq/">FAQ</a>, finalists will be selected on May 31st as follows:</p>
						<p><ol>
							<li>The video with the most YouTube likes will be entered into the final round as the Crowd Favorite Finalist.</li>
							<li>The video pitch with the most FCRC member votes (per electronic survey to be sent to all FCRC members) will be entered into the final round as the Member Favorite Finalist.</li>
							<li>The video pitch(es) deemed "best" by the FCRC Runnovation Prize committee will progress to the final round.</li>
						</ol></p>
						<p>FCRC Members: You should have received a FCRC Runnovation Prize survey in your email by the evening of Monday, May 15, 2017.  Please view the video pitches below and then fill out the survey to indicate your favorite. (Video order below is randomized with every page refresh so as to not affect the outcome of voting.)</p>
						<p>Thanks to everyone for their participation in the inaugural FCRC Runnovation Prize!</p>
<?php
	$video1='<iframe width="560" height="315" src="https://www.youtube.com/embed/kv-Kz7VPJn8?rel=0" frameborder="0" allowfullscreen></iframe>';
	$video2='<iframe width="560" height="315" src="https://www.youtube.com/embed/VQQ_4nwdKTA" frameborder="0" allowfullscreen></iframe>';
	$video3='<iframe width="560" height="315" src="https://www.youtube.com/embed/LvkES0xAOZA" frameborder="0" allowfullscreen></iframe>';
	$video4='<iframe width="560" height="315" src="https://www.youtube.com/embed/mJrU0m3iBUU" frameborder="0" allowfullscreen></iframe>';
	$video5='<iframe width="560" height="315" src="https://www.youtube.com/embed/7FOH2VY_WQQ" frameborder="0" allowfullscreen></iframe>';
	$video6='<iframe width="560" height="315" src="https://www.youtube.com/embed/h0B_BZufpmM" frameborder="0" allowfullscreen></iframe>';
	$videos = array($video1,$video2,$video3,$video4,$video5,$video6);
	shuffle($videos);
	foreach ($videos as $video) {
			echo $video;
	}
?>
					</div>
				</article>
				
				<?php comments_template( '', true ); ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<?php get_footer(); ?>