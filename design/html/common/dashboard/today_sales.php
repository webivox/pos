<?php

$todayDate = $dateCls->todayDate('Y-m-d');

$rowdbw = $db->fetch("SELECT
                        SUM(total_sale) AS total_sale
                      FROM sales_invoices
                      WHERE DATE(added_date) = '$todayDate'");

?>
<div class="dashboard_box_widget">

	<h4>Today Sales</h4>
    
    <h2>Rs.<?php echo $defCls->money($rowdbw['total_sale']); ?></h2>


</div>