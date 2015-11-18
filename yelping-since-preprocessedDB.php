<?php
	global $wpdb;
	$start_year =2004;
	$end_year =2015;
	$year_array= Array();
	$user_counts= Array();
	//initialize the year array and user increase array
	for($i = $start_year;$i<=$end_year;$i++){
		$str = strval($i);
		array_push($year_array,$str);
		array_push($user_counts,0);
	}
	//pass the php varialble to js
	echo '<script>var name_year= '.json_encode($year_array).';</script>';
	$user_counts_monthly = Array();
	//initialize the 2d array
	for($j=0;$j<12;$j++){
		array_push($user_counts_monthly,$user_counts);
	}

	//result_array is a 2-d array

		//each result in the result_array contain 50000 rows
		
	$sql = 'SELECT user_increase FROM user_increase_yearly';
	$results = $wpdb->get_results($sql);
	$i=0;
	foreach ( $results as $result ) {
		//we only interested in the year
		$s = $result->user_increase;
		$user_counts[$i] =intval($s);
		$i = $i+1;
	}
	
	//fetch the data for monthly increase
	for($j=0;$j<12;$j++){
		$year = strval($j+2004);
		$year='y'.$year;
		$sql = 'SELECT '.$year.' FROM user_increase_monthly';
		$results = $wpdb->get_results($sql);
		//var_dump($results);
		$i=0;
		foreach ( $results as $result ) {
			//we only interested in the year
			$s = $result->$year;
			$user_counts_monthly[$j][$i] =intval($s);
			$i = $i+1;
		}
	}
	//pass the array to js
	echo '<script>var y_val= '.json_encode($user_counts).';</script>';
	echo '<script>var y_val_monthly= '.json_encode($user_counts_monthly).';</script>';
?>
<script type="text/javascript">

$(function () {
    // Create the chart
	//initialize the name of the columns on the first layer
	var i =0
	var name_month = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
	var data = []
	var ddSeries = []
	var len = <?php echo (count($year_array))?>;
	//create the data for the figure
	for(i;i<len;i++){
        data.push({
            name: name_year[i],
            y:y_val[i],
			drilldown:name_year[i]
        });
    }
	
	var j =0;
	i=0;
	//create the data for the figure
	for(i;i<len;i++){
		var ddTemp = [];
		for(j;j<len;j++){
			var dTemp = [];
			dTemp.push(name_month[j],y_val_monthly[i][j]);
			ddTemp.push(dTemp);
		}
		j=0;
		ddSeries.push({
			name:name_year[i],
			id: name_year[i],
			data:ddTemp
		});
	}
     $('#user_increase').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Increase in User of Yelp from 2004 to 2015'
        },
        subtitle: {
            text: 'Click the columns to view monthly user increase'
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'User Increase'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> user increased<br/>'
        },

        series: [{
			colorByPoint: true,
			name:'User Increase',
			data: data
		}],
        drilldown: {
            series: ddSeries
        },
		credits: {
			enabled: false
		}
    });
});
</script>