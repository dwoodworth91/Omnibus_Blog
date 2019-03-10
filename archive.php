<?php
	// Variables $month, $prevmonth, $year and $prevyear are used to section the archive display
	$month = '';
	$prevmonth = '';
	$year = '';
	$prevyear = '';
	
	$serverYear = date('Y');
	$serverMonth = date('F');
?>
<ul class="dropdown-menu" id="archive-list">
	<?php if (have_posts()): while (have_posts()): the_post();
		
		// Find the month/year of the current post
		$year = mysql2date('Y', $post->post_date);
		$month = mysql2date('F', $post->post_date);
		$monthCollapse = 'collapse';
		$yearCollapse = 'collapse';
		$monthCollapseIcon = 'glyphicon-plus-sign';
		$yearCollapseIcon = 'glyphicon-plus-sign';
		if($year == $serverYear){
			$yearCollapse = 'collapse in';
			$yearCollapseIcon = 'glyphicon-minus-sign';
			if($month == $serverMonth){
				$monthCollapse = 'collapse in';
				$monthCollapseIcon = 'glyphicon-minus-sign';
			}
		}
		if($year != $prevyear){
			if($prevyear != ''){
				echo "</ul>"; //close posts list					
				echo "</li>"; //close month item
				echo "</ul>"; //close months list
				echo "</li>"; //close year item
			}
			echo "<li>"; //begin new year item
			echo "<div class = 'expand-archive' data-toggle='collapse' data-target='#year-".$year."'>";
			echo "<span class='expand-icon glyphicon ".$yearCollapseIcon."' aria-hidden='true'></span>&nbsp;";
			echo "<h4>".$year."</h4>"; //Print Year
			echo "</div>";
			echo "<ul id='year-".$year."' class='".$yearCollapse."'>"; //begin new months list
			echo "<li>"; //begin new month item
			echo "<div class = 'expand-archive' data-toggle='collapse' data-target='#month-".$month."-".$year."'>";
			echo "<span class='expand-icon glyphicon ".$monthCollapseIcon."' aria-hidden='true'></span>&nbsp;";
			echo "<h4>".$month."</h4>"; //Print Month
			echo "</div>";
			echo "<ul id='month-".$month."-".$year."' class='".$monthCollapse."'>"; //begin new posts list
			
			$prevmonth = $month;
			$prevyear = $year;
		}
		else if($month != $prevmonth){
			if($prevmonth != ''){
				echo "</ul>"; //close posts list
				echo "</li>"; //close month item
			}
			echo "<li>"; //begin new month item
			echo "<div class = 'expand-archive' data-toggle='collapse' data-target='#month-".$month."-".$year."'>";
			echo "<span class='expand-icon glyphicon ".$monthCollapseIcon."' aria-hidden='true'></span>&nbsp;";
			echo "<h4>".$month."</h4>"; //Print Month
			echo "</div>";
			echo "<ul id='month-".$month."-".$year."' class='".$monthCollapse."'>"; //begin new posts list
			$prevmonth = $month;
		}

		echo "<li class='item-archive'><a href='".get_the_permalink()."'><h4>".get_the_title()."</h4></a></li>";

	endwhile; echo "</ul></li></ul></li>"; endif; ?>
</ul>