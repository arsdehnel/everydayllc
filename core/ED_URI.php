<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ED_URI extends CI_URI {

    function __construct()
    {
        parent::__construct();
    }
    
    function segments_to_array()
    {
    	$CI =& get_instance();
    	
    	$data['controller']									= $CI->uri->segment(1);
    	$data['function']									= $CI->uri->segment(2);
    	
    	switch( $CI->uri->segment(1).'/'.$CI->uri->segment(2) ):
    	
    		case "administration/attribute":
				$data['common_attribute_master_id']			= $CI->uri->segment(3);
    			break;
    			
    		case "browse/control":
    			$data['type_code']							= $CI->uri->segment(3);
    			$data['browse_type']						= $CI->uri->segment(4);
    			$data['cao_id']								= $CI->uri->segment(5) ? explode('~',$CI->uri->segment(5)) : null;
    			break;

    		case "browse/recent":
    			$data['type_code']							= $CI->uri->segment(3);
    			break;
    			
			case "browse/user_detail":
				$data['id_field']							= $CI->uri->segment(3);
				$data['id_value']							= $CI->uri->segment(4);
				$data['status_code']						= $CI->uri->segment(5);
				break;

    		case "browse/view":
    			$data['common_item_master_id']				= $CI->uri->segment(3);
    			$data['item_title']							= $CI->uri->segment(4);
    			break;

    		case "common/confirm":
    			$data['table_name']							= $CI->uri->segment(3);
    			$data['id_field']							= $CI->uri->segment(4);
    			$data['id_value']							= $CI->uri->segment(5);
    			$data['content_code']						= $CI->uri->segment(6);
    			break;
    			
    		case "common/attribute_option_modal":
				$data['common_attribute_master_id']			= $CI->uri->segment(3);
				$data['load_id']							= $CI->uri->segment(4);
    			break;	

    		case "common/attribute_select":
    		case "common/attribute_option_inline":
				$data['common_item_master_id']				= $CI->uri->segment(3);
				$data['common_attribute_master_id']			= $CI->uri->segment(4);
				$data['load_id']							= $CI->uri->segment(5);
    			break;	
    			
    		case "common/select_images":
    			$data['common_item_master_id']				= $CI->uri->segment(4);
    			break;

    		case "common/images_options":
    		case "common/select_cover":
    			$data['common_item_master_id']				= $CI->uri->segment(3);
    			break;

			case "common/item_master":
				$data['common_item_master_id']				= $CI->uri->segment(3);
				$data['type_code']							= $CI->uri->segment(4);
				$data['item_title']							= $CI->uri->segment(5);
				break;
				
			case "contacts/group_users":
				$data['common_item_master_id']				= $CI->uri->segment(3);
				break;
   			
    		case "forum/topics":
    		case "forum/topic_list":
    			$data['discussion_category_id']				= $CI->uri->segment(3);
    			break;
    			
    		case "forum/threads":
    			$data['discussion_topic_id']				= $CI->uri->segment(3);
    			break;
    			
    		case "system/setbreadcrumbid":
    			$data['breadcrumb_id']						= $CI->uri->segment(3);
    			break;
    			
    	endswitch;
    	$CI->data['uri_data']								= $data;
    }
        
}


/* End of file welcome.php */
/* Location: ./application/core/CD_Controller.php */