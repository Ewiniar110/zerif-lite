<?php 
	
		echo '<section class="latest-news" id="stargraph">';
		
			echo '<div class="container">';

				/* SECTION HEADER */
				
				echo '<div class="section-header">';

					/* title */
					echo '<h2 class="star-header dark-text">' . __('Overall Star Distribution','zerif-lite') . '</h2>';
					/* subtitle */
					//echo '<h6 class="dark-text">Overall Star Distribution</h6>';
				echo '</div><!-- END .section-header -->';

				echo '<div class="clear"></div>';
				
					
					echo '<div id="star_graph" class="data-show-div">';
						echo '<div id="star_graph_figI" class="star-graph-figI"></div>';
						echo '<div id="star_graph_figII" class="star-graph-figII"></div>';
						echo '<div id="star_comments" class="star-comments"><p class="comments-font">The average star of businesses on Yelp is 3.7. The pie chart shows that 27.6% businesses are rated 4-star or 4.5-star while 28.9% businesses are rated lower than 3-star. Among those cities where there are more than 20 businesses registered on Yelp, the businesses in Boulder City, Neveda, have the highest star, with an average star of 4.13, while the businesses in Dorval, Quebec, Canada, have the lowest average star of 3.13.</p>';
					echo '</div>';
			echo '</div><!-- .container -->';
		echo '</section>';

 ?>