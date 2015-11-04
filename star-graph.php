 <?php
	global $wpdb;
	$star_array = array(); 
	$results =  $wpdb->get_results('SELECT stars FROM business' );
	foreach ( $results as $result ) {

	$star = $result->stars;
	array_push($star_array,$star);
	}
	$stars_count = array_count_values($star_array);
	global $x_axis,$y_axis;
	$x_axis = array();
	$y_axis = array();
	$x_axis_float = array(); // used for sortting
	//unifying the data type to string for x_axis
	foreach($stars_count as $x => $value){
		if(!is_string($x)){
			array_push($x_axis,strval($x));
			array_push($x_axis_float,floatval($x));
			}else{
				array_push($x_axis,$x);
				array_push($x_axis_float,floatval($x));
			}
		
		array_push($y_axis,$value);
	}
	//sorting according to x value
	$x_temp;
	$y_temp;
	$X_f_temp;
	$length = count($x_axis);
	//var_dump($x_axis_float);
	//var_dump(1.11);
	for($i =0;$i<($length-1);$i++){
		for($j=$i+1;$j<$length;$j++){
			if($x_axis_float[$j]<$x_axis_float[$i]){
					$x_f_temp = $x_axis_float[$j];
					$x_axis_float[$j] = $x_axis_float[$i];
					$x_axis_float[$i] = $x_f_temp;
					$x_temp = $x_axis[$j];
					$x_axis[$j] = $x_axis[$i];
					$x_axis[$i] = $x_temp;
					$y_temp = $y_axis[$j];
					$y_axis[$j] = $y_axis[$i];
					$y_axis[$i] = $y_temp;
			}
		}
	}
	//var_dump($x_axis_float);
?>
	
<script type="text/javascript">
//line chart of stars
$(function () {
    $('#star_graph').highcharts({
        title: {
            text: 'Monthly Average Temperature',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: WorldClimate.com',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode($x_axis) ?>
        },
        yAxis: {
            title: {
                text: 'Temperature'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'Tokyo',
            data: <?php echo json_encode($y_axis) ?>
        }]
    });
});

var star = <?php echo json_encode($x_axis) ?>;
var counts = <?php echo json_encode($y_axis) ?>;
var name = Array();
var data = Array();
var arrName = $.map(star, function(el) { return el });
var arrCounts = $.map(counts, function(el) { return el });
var dataArrayFinal = Array();
for(j=0;j<arrName.length;j++) { 
   var temp = new Array(arrName[j],arrCounts[j]); 
   dataArrayFinal[j] = temp;     
}

//2-d pie chart plotting
$(function () {
    $('#star_graph_pie1').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: 'Browser market shares January, 2015 to May, 2015'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: dataArrayFinal
        }]
    });
});
//3-d pie chart
$(function () {
    $('#star_graph_pie').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Browser market shares at a specific website, 2014'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: dataArrayFinal
        }]
    });
});
</script>