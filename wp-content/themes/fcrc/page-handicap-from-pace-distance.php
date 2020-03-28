<?php
/*
Template Name: handicap-from-pace-distance
*/


//get_header(); ?>
				
<?php
	// CONSTANTS & VARIABLES:
	$distance = $_POST['distance'];
	$zero_handicap_minutes = $_POST['zero_handicap_minutes'];
	$zero_handicap_seconds = $_POST['zero_handicap_seconds'];

	
	function introduction() {
		echo '<p>This tool outputs the handicaps and start times based off a runner\'s pace.';
	}
	
	function get_parameters() {
		echo '<p>Please enter the following information:</p>';
		echo '<form action="" method="post" />';
		echo '<p>Distance: <input type="text" name="distance" value="" size="3" maxlength="3" /> kilometers</p>';
		echo '<p>Pace for zero handicap: <input type="text" name="zero_handicap_minutes" value="16" size="2" maxlength="2" />:<input type="text" name="zero_handicap_seconds" value="00" size="2" maxlength="2" />/mile';
		echo '<br /><p>Runners using this tool to determine their T&H start time, or to figure out their predicted "how long will the race take me" time based off the average pace they think they will be running, will be shown that information on the next page.</p>';
		echo '<input type="submit" name="Submit" value=" Submit " />';
	}
	
	function output_handicap($pace_minute_start, $pace_minute_end) {
		global $distance, $zero_handicap_minutes, $zero_handicap_seconds;
		$zero_handicap_pace = $zero_handicap_minutes + $zero_handicap_seconds/60;
		$zero_handicap_finish_time = $zero_handicap_pace * $distance / 1.60934;
		echo '<table border="1px" width="96%"><tbody align="center">';
		echo '<tr><th width="80">Pace</th><th width="120">Start Time</th><th>Handicap</th><th width="75">Predicted Time</th></tr>';
		for ($pace_minutes=$pace_minute_start; $pace_minutes<=$pace_minute_end; $pace_minutes++) {
			for ($pace_seconds=0; $pace_seconds<=50; $pace_seconds=$pace_seconds+10) {
				// output Pace/mile
				echo '<tr><td>'.$pace_minutes.':';
				printf("%02d", $pace_seconds);
				echo '/mi.</td>';
				// some calcs
				$pace = $pace_minutes + $pace_seconds/60;
				$finish_time = $pace * $distance / 1.60934;
				$handicap = $zero_handicap_finish_time - $finish_time;
				if ($handicap >= 0) { $handicap_minutes = floor($handicap); }
				else { $handicap_minutes = ceil($handicap); }
				$handicap_seconds = round(($handicap - $handicap_minutes)*60);
				if ($handicap_seconds=='60' && $handicap<0) {$handicap_minutes=$handicap_minutes-1; $handicap_seconds=0;}
				if ($handicap_seconds=='60' && $handicap>=0) {$handicap_minutes++; $handicap_seconds=0;}
				$finish_time_minutes = floor($finish_time);
				$finish_time_seconds = round(($finish_time - $finish_time_minutes)*60);
				if ($finish_time_seconds=='60') {$finish_time_minutes++; $finish_time_seconds=0;}
				// output Start Time
				echo '<td>';
				if ($handicap < 0) { 
					echo '7:'; 
					$negative_handicap = 60 + $handicap;
					$negative_handicap_minutes = floor($negative_handicap);
					$negative_handicap_seconds = round(($negative_handicap - $negative_handicap_minutes)*60);
					if ($negative_handicap_seconds=='60' && $handicap<0) {$negative_handicap_minutes=$negative_handicap_minutes-1; $negative_handicap_seconds=0;}
					if ($negative_handicap_seconds=='60' && $handicap>=0) {$negative_handicap_minutes++; $negative_handicap_seconds=0;}
					printf ("%02d", $negative_handicap_minutes);
					echo ':';
					printf("%02d", $negative_handicap_seconds);
				}
				else { 
					echo '8:'; 
					printf ("%02d", $handicap_minutes);
					echo ':';
					printf("%02d", $handicap_seconds);
				}
				echo '</td>';
				// output Handicap
				echo '<td>'.$handicap_minutes.':';
				printf("%02d", abs($handicap_seconds));
				echo '</td>';
				// output Predicted Time
				echo '<td>'.$finish_time_minutes.':';
				printf("%02d", $finish_time_seconds);
				echo '</td>';
				echo '</tr>';
			}
		} 
		echo '</tbody></table>';
	}
	
	
	// BEGIN MAIN PROGRAM
	if ($distance == '') {
		introduction();
		get_parameters();
	}
	else {
		echo '<p>The start times based off pace/mile for <strong>'.$distance.' km</strong> and a zero handicap for '.$zero_handicap_minutes.':';
		printf ("%02d",$zero_handicap_seconds);
		echo '/mi pace are:</p>';
		echo '<table><tr><td align="left" valign="top">';
		output_handicap(5,10);
		echo '</td><td align="right"  valign="top">';
		output_handicap(11,16);
		echo '</td></tr></table>';
	}
?>
