<?php
	$q = $_REQUEST["q"];
	$i = strpos($q,",");
	$j = strripos($q,",");
	$city = substr($q,0,$i);
	$zip_code = substr($q,$i+1,$j-$i-1);
	if($j<strlen($q)-1){
		$category = substr($q,$j+1,strlen($q)-$j-1);
	}else{
		$category = "";
	}
	$locations = array();
	
	if($city !==""){
	   
	   
	   //fetch the lat and lng of resturants according to the city and zip code;
	   if($zip_code!=="" and $category!==""){
			if($category!=="All"){
				$sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE zip_code="'.$zip_code.'" AND city="'.$city.'" AND category="'.$category.'"';
			}else{
				$sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE zip_code="'.$zip_code.'" AND city="'.$city.'"';
			}
		}
	   elseif($zip_code!=="" and $category==="" ){
		   $sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE zip_code="'.$zip_code.'" AND city="'.$city.'"';
	   }elseif($zip_code==="" and $category!==""){
		   if($category!=="All"){
		   $sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE city="'.$city.'" AND category="'.$category.'"';
		   }else{
			$sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE city="'.$city.'"';   
		   }
	   }elseif($zip_code==="" and $category===""){
		   $sql='SELECT latitude,longitude,name,category FROM business_distribution WHERE city="'.$city.'"';
	   }
	   $con = mysqli_connect('localhost', 'root', '');
		mysqli_select_db($con,"data_vis");
		$results = mysqli_query($con,$sql);
		$lg = array();
		$lat = array();
		$name = array();
		$category = array();
		while($row = mysqli_fetch_array($results)) {
			array_push($lat,$row['latitude']);
			array_push($lg,$row['longitude']);
			array_push($name,$row['name']);
			array_push($category,$row['category']);
		}
		
		array_push($locations,$lat);
		array_push($locations,$lg);
		array_push($locations,$name);
		array_push($locations,$category);
		echo json_encode($locations);
	   
	}else{
		$lg = array();
		$lat = array();
		$name = array();
		$category = array();
		array_push($locations,$lat);
		array_push($locations,$lg);
		array_push($locations,$name);
		array_push($locations,$category);
		echo json_encode($locations);
	}
	
?>