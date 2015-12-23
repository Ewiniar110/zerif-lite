 <?php
	global $wpdb;
	$star_counts = array();
	$star_array= Array();
	$average_star = 0;
	$all_counts = 0;
	//initialize the star array and counts array
	for($i = 0;$i<9;$i++){
		array_push($star_array,strval($i*0.5+0.5));
		array_push($star_counts,0);
	}
	$results =  $wpdb->get_results('SELECT stars FROM yelp_business' );
	foreach ( $results as $result ) {
	//calculate counts
	$star = $result->stars;
	$star_float = floatval($star);
	$index = intval($star_float/0.5) - 2;
	$star_counts[$index]++;
	$average_star +=floatval($star);
	$all_counts++;
	}
	$average_star = $average_star/$all_counts;
	echo '<script>var average_star='.json_encode($average_star).'</script>';
?>
	
<script type="text/javascript">

console.log(average_star);
var star = <?php echo json_encode($star_array) ?>;
var counts = <?php echo json_encode($star_counts) ?>;
var name = Array();
var data = Array();
var arrName = $.map(star, function(el) { return el });
var arrCounts = $.map(counts, function(el) { return el });
var dataArrayFinal = Array();
for(j=0;j<arrName.length;j++) { 
   var temp = new Array(arrName[j],arrCounts[j]); 
   dataArrayFinal[j] = temp;     
}
//3-d pie chart
$(function () {
    $('#star_graph_figII').highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'Overall Business Star Pie Chart'
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
            name: 'Ratio',
            data: dataArrayFinal
        }],
		credits: {
			enabled: false
		}
    });
});

//line chart of stars
$(function () {
    $('#star_graph_figI').highcharts({
		chart: {
            type: 'column'
        },
        title: {
            text: 'Overall Business Star',
            x: -20 //center
        },
        xAxis: {
            categories: <?php echo json_encode($star_array) ?>
        },
        yAxis: {
            title: {
                text: 'Business Counts'
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
            name: 'Stars',
            data: <?php echo json_encode($star_counts) ?>
        }],
		credits: {
			enabled: false
		}
    });
});
</script>