<div class="dashboard_table_widget">
<div class="dashboard_table_widget_in">

	<h3>Upcoming Issued Cheques (Next 7 Days)</h3>
    
    <table class="table">
    
    	<thead>
            <tr>
            
                <td>Cheque Date</td>
                <td>Added Date</td>
                <td>Cheque No</td>
                <td>Amount</td>
                <td>Remarks</td>
            
            </tr>
        </thead>
    
    	<tbody>
        	
            
            
            <?php
			$afterSevenDays = date('Y-m-d', strtotime('+7 days'));

            $sql = "WHERE `type`='Issued' AND cheque_date<='".$afterSevenDays."' AND status=0 ORDER BY cheque_date ASC";
            $cheques = $AccountsTransactionChequeQuery->gets($sql);
            foreach($cheques as $cheque)
            {
                
                ?>
                <tr>
                
                    <td><?php echo $dateCls->showDate($cheque['cheque_date']); ?></td>
                    <td><?php echo $dateCls->showDate($cheque['added_date']); ?></td>
                    <td><?php echo $defCls->showText($cheque['cheque_no']); ?></td>
                    <td><?php echo $defCls->money($cheque['amount']); ?></td>
                    <td><?php echo $defCls->showText($cheque['remarks']); ?></td>
                
                </tr>
           <?php } ?>
       </tbody>
    
    
    </table>


</div>
</div>