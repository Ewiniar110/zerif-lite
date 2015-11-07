<?php
	global $wpdb;
	$state_array= Array();
	$user_counts= Array();
	$state_results= $wpdb->get_results('SELECT DISTINCT state FROM yelp_business');
	foreach ( $state_results as $state_result ) {
				$state = $state_result->state;
				array_push($state_array,$state);
				array_push($user_counts,0);
			}
	//row counts in the table of yelp_user
	$row_count = $wpdb->get_results('SELECT COUNT(state) FROM yelp_business');
	//convert the type to integer
	$row_count = intval($row_count[0]->{'COUNT(state)'});
	
	//fetch data from yelp_user table with a while loop
	//result_array is a 2-d array
	//var_dump($state_array);
	$temp_count =0;
	while($temp_count <$row_count){
		//each result in the result_array contain 30000 rows
		if(($row_count - $temp_count) >= 30000){
			$sql = 'SELECT state FROM yelp_business WHERE attributes_Parking_lot = "true" LIMIT '.strval(30000).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//we only interested in the year
				$s = $result->state;
				//for plotting in highchart, we can not use associative array. This way, we need to first get the index of int and save the count
				$index = array_search($s,$state_array);
				$user_counts[$index] +=1;
			}
			$temp_count = $temp_count+30000;
		}else{
			// the last group of rows
			$sql = 'SELECT state FROM yelp_business WHERE attributes_Parking_lot = "true" LIMIT '.strval($row_count-$temp_count).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//we only interested in the year
				$s = $result->state;
				$index = array_search($s,$state_array);
				$user_counts[$index] +=1;
			}
			$temp_count = $row_count+1;
		}	
	}
	var_dump($user_counts);
?>
<?php var_dump($state_array); ?>
<script type="text/javascript">
$(function () {
    $('#user_increase').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'Historic and Estimated Worldwide Population Growth by Region'
        },
        subtitle: {
            text: 'Source: Wikipedia.org'
        },
        xAxis: {
            categories: <?php echo json_encode($state_array) ?>,
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            labels: {
                formatter: function () {
                    return this.value;
                }
            }
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            area: {
                stacking: 'normal',
                lineColor: '#666666',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#666666'
                }
            }
        },
        series: [{
            name: 'User Increase',
            data: <?php echo json_encode($user_counts) ?>
        }]
    });
});
</script>