 <?php
	global $wpdb;
	$star_counts = array();
	$star_array= Array();
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
	}
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
            categories: <?php echo json_encode($star_array) ?>
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
            data: <?php echo json_encode($star_counts) ?>
        }]
    });
});

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

//2-d pie chart plotting
$(function () {
    $('#star_graph_pie11').highcharts({
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