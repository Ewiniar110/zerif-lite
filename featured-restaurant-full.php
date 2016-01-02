<?php
	global $wpdb;
	$sql = 'SELECT * FROM resturant_category';
	$results = $wpdb->get_results($sql);
	//save the percentage in each array;
	//they are associative arrays
	$cate_name = array("American","Mexican","Chinese","Italian","Japanese","French","Thai","Indian","Korean","Greek");
	$state_name = array("Neveda","Wisconsin","Illinois","Pennsylvania","North Carolina","Arizona","Quebec","Ontario");
	$categories = array("american"=>array(),"mexican"=>array(),"chinese"=>array(),"italian"=>array(),"japanese"=>array(),"french"=>array(),"thai"=>array(),"indian"=>array(),"korean"=>array(),"greek"=>array());
	$all_categories = array();
	
	foreach($results as $result){
		$i = 0;
		$state = $result->State;
		$temp = array();
		for(;$i<count($cate_name)-1;$i++){
			$temp[$cate_name[$i]] = floatval($result->$cate_name[$i]);
		}
		$all_categories[$state] = $temp;
		
	}
	echo '<script>var all_categories= '.json_encode($all_categories).'</script>';
	echo '<script>var state_name= '.json_encode($state_name).'</script>';
	echo '<script>var cate_name= '.json_encode($cate_name).'</script>';
	//var_dump($all_categories);
?>

<script type="text/javascript">
var i = 0;
var data = [];
var j =0;
var data1 = [];
for(;i<cate_name.length;i++){
	var temp = [];
	var temp1 = [];
	j=0;
	for(;j<state_name.length;j++){
		temp.push(all_categories[state_name[j]][cate_name[i]]);
	}
	data1.push({
		name:cate_name[i],
		data:temp
	});
}

$(function () {
    $('#resturant_category_figI').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'Featured Resturant Distribution by Region'
        },
        xAxis: {
            categories: state_name,
            tickmarkPlacement: 'on',
            title: {
                enabled: false
            }
        },
        yAxis: {
            title: {
                text: 'Percent'
            }
        },
        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.percentage:.1f}%</b><br/>',
            shared: true
        },
        plotOptions: {
            area: {
                stacking: 'percent',
                lineColor: '#ffffff',
                lineWidth: 1,
                marker: {
                    lineWidth: 1,
                    lineColor: '#ffffff'
                }
            }
        },
        series:data1,
		credits: {
			enabled: false
		}
    });
});
</script>