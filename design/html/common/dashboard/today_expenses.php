<?php

$todayDate = $dateCls->todayDate('Y-m-d');

$rowdbw = $db->fetch("SELECT
                        SUM(amount) AS amount
                      FROM accounts_expences
                      WHERE DATE(added_date) = '$todayDate'");

?>
<div class="dashboard_box_widget">

	<h4>Today Expences</h4>
    
    <h2>Rs.<?php echo $defCls->money($rowdbw['amount']); ?></h2>


</div>