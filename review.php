<?php
	global $wpdb;
	
	$star_counts = array();
	$star_array= Array();
	//initialize the star array and counts array
	for($i = 0;$i<5;$i++){
		array_push($star_array,strval($i+1));
		array_push($star_counts,0);
	}
	//row counts in the table of yelp_review
	//$row_count = $wpdb->get_results('SELECT COUNT(votes_useful) FROM yelp_review');
	//convert the type to integer
	//$row_count = intval($row_count[0]->{'COUNT(votes_useful)'});
	//fetch data from yelp_user table with a while loop
	//result_array is a 2-d array
	//var_dump($row_count);
	$row_count=1000000;
	$temp_count =0;
	while($temp_count <$row_count){
		//each result in the result_array contain 50000 rows
		if(($row_count - $temp_count) >= 20000){
			$sql = 'SELECT stars,votes_useful FROM yelp_review LIMIT '.strval(20000).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//record the stars of voted review
				$s = $result->votes_useful;
				$s = intval($s);
				if($s>0){
					$star= $result->stars;
					$star_int= intval($star);
					$index = $star_int - 1;
					$star_counts[$index]++;
				}
			}
			$temp_count = $temp_count+20000;
		}else{
			// the last group of rows
			$sql = 'SELECT stars,votes_useful FROM yelp_review LIMIT '.strval($row_count-$temp_count).' OFFSET '.strval($temp_count);
			$results = $wpdb->get_results($sql);
			foreach ( $results as $result ) {
				//record the stars of voted review
				$s = $result->votes_useful;
				$s = intval($s);
				if($s>0){
					$star= $result->stars;
					$star_int= intval($star);
					$index = $star_int - 1;
					$star_counts[$index]++;
				}
			}
			$temp_count = $row_count+1;
		}	
	}
	var_dump($star_counts);
?>
<script type="text/javascript">
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