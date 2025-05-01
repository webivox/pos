<div id="quick_menu_ul">
        
    <ul>
    
        <?php
        foreach($data['categoryList'] as $c)
        {
        ?>
        <li><a class="getItemsQM" data-id="<?php echo $c['catId']; ?>"><?php echo $c['name']; ?></a></li>
        <?php } ?>
    
    </ul>

</div>