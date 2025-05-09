<?php

$startOfMonth =  $dateCls->todayDate('Y-m-').'01';// First day of current month
$endOfMonth =  $dateCls->todayDate('Y-m-d');    // Today

$rowdbw = $db->fetch("SELECT
                        SUM(amount) AS amount
                      FROM customers_settlements
                      WHERE DATE(added_date) BETWEEN '$startOfMonth' AND '$endOfMonth'");
?>

<div class="dashboard_box_widget">
	<h4>Monthly Settlement</h4>
    <h2>Rs.<?php echo $defCls->money($rowdbw['amount']); ?></h2>
</div>
