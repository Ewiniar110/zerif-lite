<?php
	global $wpdb;
	$start_year =2004;
	$end_year =2014;
	$year_array= Array();
	$user_counts= Array();
	//initialize the year array and user increase array
	for($i = $start_year;$i<=$end_year;$i++){
		$str = strval($i);
		array_push($year_array,$str);
		array_push($user_counts,0);
	}
	//row counts in the table of yelp_user
	$row_count = $wpdb->get_results('SELECT COUNT(yelping_since) FROM yelp_user');
	//convert the type to integer
	$row_count = intval($row_count[0]->{'COUNT(yelping_since)'});
	
	//fetch data from yelp_user table with a while loop
	//result_array is a 2-d array

	$temp_count =0;
	while($temp_count <$row_count){
		//each result in the result_array contain 50000 rows
		if(($row_count - $temp_count) >= 50000){
			$sql = 'SELECT yelping_since FROM yelp_user LIMIT '.strval(50000).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//we only interested in the year
				$s = $result->yelping_since;
				$s = substr($s,0,4);
				$year = intval($s)-intval('2004');
				$user_counts[$year] +=1;
			}
			$temp_count = $temp_count+50000;
		}else{
			// the last group of rows
			$sql = 'SELECT yelping_since FROM yelp_user LIMIT '.strval($row_count-$temp_count).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//we only interested in the year
				$s = $result->yelping_since;
				$s = substr($s,0,4);
				$year = intval($s)-intval('2004');
				$user_counts[$year] +=1;
			}
			$temp_count = $row_count+1;
		}	
	}
?>
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
            categories: <?php echo json_encode($year_array) ?>,
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