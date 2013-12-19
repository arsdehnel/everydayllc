<?php
    if( $dataset->num_rows() > 0 ):
    	if( !isset( $datagrid_style ) ):
    		$datagrid_style = '';
    	endif;
    	if( isset($checkbox) ):
    		echo '<div id="form_error_wrapper"></div>';
    		echo '<form id="datagrid_form" method="post">';
    	endif;
	    if( $dataset->num_rows() > 20 ):
            if( isset($buttons) && is_array( $buttons ) && count( $buttons ) > 0 ):
                echo '<div class="button_group"><ul>';
                foreach( $buttons as $button ):
                	echo '<li>';
                	if( isset( $button['options'] ) && is_array( $button['options'] ) ):
                		$arrow 	= '<span class="arrow"></span>';
                	else:
                		$arrow 	= null;
                	endif;
                    echo "<a class='btn ".$button['class']."' href='".$button['href']."'>".$button['label'].$arrow."</a>";
                    if( isset( $button['options'] ) && is_array( $button['options'] ) ):
                    	echo '<ul>';
                    	foreach( $button['options'] as $option ):
                    		echo '<li><a href="'.$option['href'].'" class="'.$option['class'].'" data-value="'.$option['data-value'].'" data-input_name="'.$option['data-input_name'].'">'.$option['label'].'</a></li>';
                    	endforeach;
                    	echo '</ul>';
                    endif;
                	echo '</li>';
                endforeach;
                echo '</ul></div>';
            endif;
        endif;
        
        if( isset( $filters ) && is_array( $filters ) ):
        	echo '<form name="datagrid_filter" id="datagrid_filter">';
        	foreach( $filters as $filter ):
				echo '<div><label for="'.$filter['field'].'">'.$filter['label'].'</label>';
				switch( $filter['type'] ):
					case "plain_text":
						echo '<input type="text" name="'.$filter['field'].'" id="'.$filter['field'].'" />';
						break;
				endswitch;
				echo '</div>';
        	endforeach;
        	echo '<div style="clear: both;"><button type="submit" id="datagrid_filter_submit">Refresh</button></div>';
        	echo '</form>';
        endif;
        ?>
        <table class="datagrid" cellpadding="0" cellspacing="0" border="0" style="<?=$datagrid_style;?>">
            <thead>
                <tr>
                    <?php
                        if( isset($checkbox) ):
                            echo '<th>Select</th>';
                        endif;
                        foreach ($dataset->list_fields() as $field):
                            if( $field != 'id' ):
                                echo '<th>'.$field.'</th>';
                            endif;
                        endforeach;
                        if( isset($actions) && is_array( $actions ) && count( $actions ) ):
                            echo '<th>Actions</th>';
                        endif;
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($dataset->result_array() as $datarec):
                        ?>
                        <tr>
                            <?php
		                        if( isset($checkbox) ):
		                            echo '<td>'.form_checkbox($checkbox, $datarec['id']).'</td>';
		                        endif;
                                foreach( $datarec as $key => $value):
                                    if( $key != 'id' ):
                                        ?>
                                        <td data-field_name="<?=$key;?>">
                                            <?php
                                                echo $value;
                                            ?>
                                        </td>
                                        <?php
                                    endif;
                                endforeach;
                                if( isset($actions) && is_array( $actions ) && count( $actions ) > 0 ):
                                    echo '<td class="actions">';
                                    foreach( $actions as $action ):
                                        echo "<a class='btn btn-mini ".$action['class']."' href='".str_replace('%id%',$datarec['id'],$action['href'])."'>".$action['label']."</a>";
                                    endforeach;
                                    echo '</td>';
                                endif;
                            ?>
                        </tr>
                        <?php
                    endforeach;
                ?>
            </tbody>
        </table>
        <script src="/scripts/datagrid.js"></script>
        <?php
            if( isset($buttons) && is_array( $buttons ) && count( $buttons ) > 0 ):
                echo '<div class="btn-toolbar">';
                foreach( $buttons as $button ):
                    echo "<a class='btn ".$button['class']."' href='".$button['href']."'><span><span>".$button['label']."</span></span></a>";
                endforeach;
                echo '</div>';
            endif;
    	if( isset($checkbox) ):
    		echo '</form><!--#datagrid_form-->';
    	endif;
    else:
        echo '<h3>No data found</h3>';
    endif;