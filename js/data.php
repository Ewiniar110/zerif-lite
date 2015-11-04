<?php

/**
 * @author lolkittens
 * @copyright 2013
 */

$con=mysql_connect('localhost','root','');
mysql_select_db('data_vis', $con);
$result=mysql_query('select * from sales order by id');
while($row = mysql_fetch_array($result)) {
  echo $row['month'] . "\t" . $row['amount']. "\n";
}

?>