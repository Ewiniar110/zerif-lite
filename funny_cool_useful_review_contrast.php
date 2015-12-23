<?php
	global $wpdb;
	$sql = 'SELECT * FROM funny_cool_useful_review_contrast';
	$results = $wpdb->get_results($sql);
	$funny_stars = Array();
	$funny_ratio = Array();
	$cool_stars = Array();
	$cool_ratio = Array();
	$useful_stars = Array();
	$useful_ratio = Array();
	$all_stars = Array();
	$all_ratio = Array();
	$name = array("funny_stars","funny_ratio","cool_stars","cool_ratio","useful_stars","useful_ratio","all_stars","all_ratio");
	//2-d array to store the star distribution
	$stars_arr = array("funny_stars"=>array(),"funny_ratio"=>array(),"cool_stars"=>array(),"cool_ratio"=>array(),"useful_stars"=>array(),"useful_ratio"=>array(),"all_stars"=>array(),"all_ratio"=>array());
	//fectch the data from the database and save it in a 2-d array
	foreach($results as $result){
		$i = 0;
		for(;$i<count($stars_arr);$i++){
			array_push($stars_arr[$name[$i]],floatval($result->$name[$i])*100); 
		}
	}
	//create a javascript array
	echo '<script>var stars_arr= '.json_encode($stars_arr).'</script>';
	echo '<script>var name_arr= '.json_encode($name).'</script>';
?>

<script type="text/javascript">
var i = 0;
var data =[];
for(i;i<stars_arr.length/2;i++){
	var temp =[];
	var j =0;
	for(j;j<5;j++){
		temp.push(stars_arr[name_arr[i*2+1]][j]*100);
	}
	data.push({
		name:name_arr[i*2+1],
		data:temp
	});
}
	$(function () {
    $('#review_comparison_fig').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Star of Voted Reviews'
        },
        xAxis: {
            categories: ['1 Star', '2 Star', '3 Star', '4 Star','5 Star'],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Percentage',
                align: 'high'
            },
            labels: {
                overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: '%'
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Useful Review',
            data: stars_arr['useful_ratio']
        },{
            name: 'Cool Review',
            data: stars_arr['cool_ratio']
        }, {
            name: 'Funny Review',
            data: stars_arr['funny_ratio']
        }]
    });
});
</script>