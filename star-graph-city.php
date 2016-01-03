 <?php
	global $wpdb;
	$city_star= Array();
	$city_array = array();
	$city_business_counts = array();
	//initialize the star array and counts array
	
	$results =  $wpdb->get_results('SELECT stars,city FROM yelp_business' );
	
	foreach ( $results as $result ) {
	
	$star = $result->stars;
	$star_float = floatval($star);
	$city = $result->city;
	
	if(!in_array($city,$city_array)){
		array_push($city_array,$city);
		array_push($city_star,$star_float);
		array_push($city_business_counts,1);
	}else{
		$city_index = array_search($city,$city_array);
		$city_star[$city_index] += $star_float;
		$city_business_counts[$city_index]++;
	}
	
	
	
	
	}
	class PQMin extends SplPriorityQueue 
	{ 
		public function compare($priority1, $priority2) 
		{ 
			if ($priority1 === $priority2) return 0; 
			return $priority1 < $priority2 ? -1 : 1; 
		} 
	} 

	class PQMax extends SplPriorityQueue 
	{ 
		public function compare($priority1, $priority2) 
		{ 
			if ($priority1 === $priority2) return 0; 
			return $priority1 < $priority2 ? 1 : -1; 
		} 
	} 
	$objPQMin = new PQMin();
	$objPQMax = new PQMax();
	$objPQMin->setExtractFlags(PQMin::EXTR_PRIORITY);
	$objPQMax->setExtractFlags(PQMax::EXTR_PRIORITY);
	
	for($i = 0;$i<count($city_array);$i++){
		$city_star[$i] = $city_star[$i]/$city_business_counts[$i];
		//find the 10 cities with the smallest star
		if($city_business_counts[$i]>20){
			if($objPQMin->count()< 10 ){
				$objPQMin->insert($city_array[$i],$city_star[$i]);
			}else{
			
				if($objPQMin->top() > $city_star[$i]){	
					$objPQMin->extract();
					$objPQMin->insert($city_array[$i],$city_star[$i]);
				}
			}
			//find the 10 cities with the largest star
			if($objPQMax->count()<10 ){
				$objPQMax->insert($city_array[$i],$city_star[$i]);
			}else{
				if($objPQMax->top() < $city_star[$i]){	
					$objPQMax->extract();
					$objPQMax->insert($city_array[$i],$city_star[$i]);
				}
			}
		}
	}
	//point to the top of the queue
	$objPQMin->top(); 
	$objPQMax->top();
	//extract both the priority and the data
	//data is the city while priority is the average business star in this city
	$objPQMin->setExtractFlags(PQMin::EXTR_BOTH);
	$objPQMax->setExtractFlags(PQMax::EXTR_BOTH);
	
	$top_city = array();
	$top_star = array();
	$last_city = array();
	$last_star = array();
	
	while($objPQMin->valid()){ 
		array_push($last_city,$objPQMin->current()['data']);
		array_push($last_star,$objPQMin->current()['priority']); 		
		$objPQMin->next(); 
	}	
	
	while($objPQMax->valid()){ 
		array_push($top_city,$objPQMax->current()['data']);
		array_push($top_star,$objPQMax->current()['priority']); 
		$objPQMax->next();
	}
?>