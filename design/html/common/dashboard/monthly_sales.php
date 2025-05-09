<?php

$startOfMonth =  $dateCls->todayDate('Y-m-').'01';// First day of current month
$endOfMonth =  $dateCls->todayDate('Y-m-d');    // Today

$rowdbw = $db->fetch("SELECT
                        SUM(total_sale) AS total_sale
                      FROM sales_invoices
                      WHERE DATE(added_date) BETWEEN '$startOfMonth' AND '$endOfMonth'");
?>

<div class="dashboard_box_widget">
	<h4>Monthly Sales</h4>
    <h2>Rs.<?php echo $defCls->money($rowdbw['total_sale']); ?></h2>
</div>
