<div id="quick_menu_item_ul">
        
    <ul>
    
        <?php
        foreach($data['itemList'] as $c)
        {
        ?>
        	<li>
            	<a class="quickmenuitem" data-id="<?php echo $c['itemId']; ?>">
                
                	<img src="<?php echo _UPLOADS.'items/no-products-found.png'; ?>" alt="">
                
                	<div class="det">
						<?php echo $c['name']; ?>
                        <br>
						Rs.<?php echo $c['price']; ?>
                    </div>
                </a>
            </li>
        <?php } ?>
    
    </ul>

</div>