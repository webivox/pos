<?php

$startOfMonth =  $dateCls->todayDate('Y-m-').'01';// First day of current month
$endOfMonth =  $dateCls->todayDate('Y-m-d');    // Today

$rowdbw = $db->fetch("SELECT
                        SUM(closing_balance) AS closing_balance
                      FROM customers_customers
                      WHERE closing_balance>0");
?>

<div class="dashboard_box_widget">
	<h4>Customer Outstanding</h4>
    <h2>Rs.<?php echo $defCls->money($rowdbw['closing_balance']); ?></h2>
</div>
