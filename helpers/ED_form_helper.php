<?php

	function form_control_group( $type, $name, $label, $value, $options, $class, $note, $data )
	{
		if( is_array( $class ) ):
			extract($class);		
		else:
			$input_class = $class;
			$control_class = null;
		endif;
		echo '<div class="control-group '.(isset( $control_class ) ? $control_class : null).'">';
		echo '<label class="control-label" for="'.$name.'">'.$label.'</label>';
		echo '<div class="controls">';
		if( is_array( $data ) ):
			$data_str = ' ';
			foreach( $data as $data_label => $data_value ):
				$data_str .= $data_label.'="'.$data_value.'" ';
			endforeach;
		else:
			$data_str = null;
		endif;
		switch( strtolower( $type ) ):
			case "email_address":
				$data['type']	= "email";
			case "freeform_small":
			case "plain_text":
				$data = array(
								'name'        => $name,
								'id'          => isset( $id ) ? $id : null,
								'value'       => set_value( $name, $value ),
								'class'		  => isset( $input_class ) ? $input_class : null
				            );
				echo form_input($data);
				break;
			case "single_select":
			case "select":
				if( strpos( $input_class, 'inline-add' ) ):
					$options['other']	= 'Other (add your own)';
				endif;
				echo form_dropdown($name, $options, set_value($name, $value ), ( is_null( $input_class ) ? null : ' class="'.$input_class.'"' ) . $data_str );
				break;
			case "textarea":
				$textarea	 = array(	 'name'		=> $name
										,'class'	=> $input_class
										,'value'	=> set_value($name, $value )
									);
				echo form_textarea($textarea);
				break;
			case "date_span":
				$data = array(
								'name'        		=> $name
							   ,'id'          		=> isset( $id ) ? $id : null
							   ,'value'       		=> isset( $value ) ? $value : date("m/d/Y")
							   ,'data-date-format'	=> 'mm/dd/yyyy'
							   ,'data-toggle'		=> 'datepicker'
				            );
				echo form_input($data);
				echo ' to ';
				$data = array(
								'name'        		=> $name
							   ,'id'          		=> isset( $id ) ? $id : null
							   ,'value'       		=> isset( $value ) ? $value : date("m/d/Y")
							   ,'data-date-format'	=> 'mm/dd/yyyy'
							   ,'data-toggle'		=> 'datepicker'								
				            );
				echo form_input($data);
				break;
			case "multi_select":
				if( array_key_exists( $common_attribute_master_id, $attribute_values ) ):
					echo form_multiselect('common_attribute_master['.$common_attribute_master_id.'][]', $attribute_values[$common_attribute_master_id] );
				else:
					echo 'No options exist';
				endif;
				break;
		endswitch;
		if( !is_null( $note ) ):
			echo '<p class="help-block">'.$note.'</p>';
		endif;
		echo '</div>';
		echo '</div>';

	}
	
	function form_button_toolbar( $toolbar_class, $toolbar_id, $group_id, $buttons, $include_spinner = TRUE, $spinner_id = 'modal-spinner' )
	{
		$return  = '<div class="'.$toolbar_class.'" id="'.$toolbar_id.'">';
		$return .= '<div class="btn-group" id="'.$group_id.'">';
		foreach( $buttons as $button ):
			$return .= '<button';
			foreach( $button as $attr => $value ):
				if( $attr != 'text' ):
					$return .= ' '.$attr.'="'.$value.'"';
				endif;
			endforeach;
			$return .= '>'.$button['text'].'</button>';
		endforeach;
		$return .= '</div>';	//end btn-group
		if( $include_spinner ):
			$return .= '<div class="spinner pull-right hide" id="'.$spinner_id.'"></div>';
		endif;
		$return .= '</div>';	//end btn-toolbar		
		return $return;

	}
	
	function form_open($action = '', $attributes = '', $hidden = array())
	{
		$CI =& get_instance();

		if ($attributes == '')
		{
			$attributes = 'method="post"';
		}

		if( is_null($action) )
		{
			$action = current_url();
		}
		elseif ($action && strpos($action, '://') === FALSE)
		{
			$action = $CI->config->site_url($action);
		}

		$form = '<form action="'.$action.'"';

		$form .= _attributes_to_string($attributes, TRUE);

		$form .= '>';

		// CSRF
		if ($CI->config->item('csrf_protection') === TRUE)
		{
			$hidden[$CI->security->csrf_token_name] = $CI->security->csrf_hash;
		}

		if (is_array($hidden) AND count($hidden) > 0)
		{
			$form .= sprintf("\n<div class=\"hidden\">%s</div>", form_hidden($hidden));
		}

		return $form;
	}

	
/* End of file */
/* Location: ./helpers/CD_form_helper.php */