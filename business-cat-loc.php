<?php
	//this php file analyzes the distribution of the location and catagory of business
	global $wpdb;
	
	$star_counts = array();
	$star_array= Array();
	//initialize the star array and counts array
	for($i = 0;$i<5;$i++){
		array_push($star_array,strval($i+1));
		array_push($star_counts,0);
	}
	//row counts in the table of yelp_review
	//$row_count = $wpdb->get_results('SELECT COUNT(votes_useful) FROM yelp_review');
	//convert the type to integer
	//$row_count = intval($row_count[0]->{'COUNT(votes_useful)'});
	//fetch data from yelp_user table with a while loop
	//result_array is a 2-d array
	//var_dump($row_count);
	$row_count=5;
	$temp_count =0;
	while($temp_count <$row_count){
		//each result in the result_array contain 50000 rows
		if(($row_count - $temp_count) >= 20000){
			$sql = 'SELECT catagories FROM yelp_business LIMIT '.strval(20000).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//record the stars of voted review
				$s = $result->votes_useful;
				$s = intval($s);
				if($s>0){
					$star= $result->stars;
					$star_int= intval($star);
					$index = $star_int - 1;
					$star_counts[$index]++;
				}
			}
			$temp_count = $temp_count+20000;
		}else{
			// the last group of rows
			$sql = 'SELECT categories FROM yelp_business LIMIT '.strval($row_count-$temp_count).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//record the stars of voted review
				$s = $result->categories;
				//var_dump($s);
			}
			$temp_count = $row_count+1;
		}	
	}
?>