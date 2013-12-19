<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Layout_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function retrieve_menu( $data )
    {
    	$site_id	= $this->session->userdata('site_id');
    	$sec_level	= $this->session->userdata('security_level');
    	$user_id	= $this->session->userdata('user_id');
    	
    	$query 		= $this->db->query("SELECT display_text
	        								,url
	        								,class
	        								,style 
	        							FROM arsdehnel_core.menu_control_detail mcd
	        							  INNER JOIN arsdehnel_core.menu_control_master mcm ON mcm.menu_control_master_id = mcd.menu_control_master_id
	        							WHERE mcm.site_id = $site_id
	        							  AND mcm.code = '$data'
	        							  AND mcm.status_code = 'A'
	        							  AND mcd.status_code = 'A'
	        							  AND mcd.view_security_level <= $sec_level
	        							ORDER BY mcd.sort_order");
        return $query;
    }
    
    function retrieve_breadcrumbs()
    {
    	$return = array();
    	switch( $this->data['uri_data']['controller'] ):
    	
    		case "forum":
    			$return	= $this->_forum_breadcrumbs();
    			break;
    			
    		case "friends":
    			$return	= $this->_friends_breadcrumbs();
    			break;
    		
			case "access":
				$return = $this->_access_breadcrumbs();
				break;

    		case "browse":
    		case "common":
    			switch( $this->data['uri_data']['function'] ):
    				case 'control':
    				case "recent":
    					$type_code = $this->data['uri_data']['type_code'];
		    			break;
		    		case "item_master":
		    		case "view":
				    	$this->load->model("Common_model");
		    			$item_master 	= $this->Common_model->retrieve_common_item_master( $this->data['uri_data'] );
		    			$type_code 		= $item_master->type_code;
	    		endswitch;
				$return = $this->_common_item_master_breadcrumbs( strtolower( $type_code ), isset( $item_master ) ? $item_master : array() );
    	endswitch;
    	
    	return $return;
    }
    
    function _access_breadcrumbs()
    {
		switch( $this->data['uri_data']['function'] ):
			case "confirm":
				$return['page_title']	= 'confirm your account';
				$return['crumbs']		= array( "cuotadera home" 		=> "/cuotadera"
												,"confirm your account" => null );
				break;
		endswitch;
		return isset( $return ) ? $return : null;
    }
    
    function _forum_breadcrumbs()
    {
    	$this->load->model("Forum_model");
		$return['header_icon']	= '/images/subhead/forum.png';
		$return['help']			= '/help/forum';
		switch( $this->data['uri_data']['function'] ):
			case "index":
			case null:
				$return['page_title']	= 'forum';
				$return['crumbs']		= array( "cuotadera home" 	=> "/base"
												,"forum home" 		=> null );
				break;
			case "topics":
				$category				= $this->Forum_model->retrieve_category( $this->data['uri_data']['discussion_category_id'] );
				$return['page_title']	= $category->category_title;
				$return['crumbs']		= array( "cuotadera home" 			=> "/base"
												,"forum home" 				=> "/forum"
												,$category->category_title	=> null  );
				break;
			case "threads":
				$topic					= $this->Forum_model->retrieve_topic( $this->data['uri_data']['discussion_topic_id'] );
				$category				= $this->Forum_model->retrieve_category( $topic->discussion_category_id );
				$return['page_title']	= $topic->topic_subject;
				$return['crumbs']		= array( "cuotadera home" 			=> "/base"
												,"forum home" 				=> "/forum"
												,$category->category_title	=> "/forum/topics/".$topic->discussion_category_id."/".preg_replace('/[^a-zA-Z0-9_]/s','',$category->category_title)
												,$topic->topic_subject	=> null  );
				break;
		endswitch;
		return $return;
    }

    function _friends_breadcrumbs()
    {
    	$site_id	= $this->session->userdata('site_id');
    	$sec_level	= $this->session->userdata('security_level');
    	$this->load->model("Friends_model");
		$return['header_icon']	= '/images/subhead/friends.png';
		$return['help']			= '/help/friends';
		$return['page_title']	= 'friends';
		$return['crumbs']		= array( "cuotadera home" 	=> "/base"
										,"friends home" 	=> null );
		$return['subnav']		= $this->db->query("SELECT mim.display_text
				        								  ,mim.url
				        								  ,mim.page_title
				        								  ,mim.class
				        								  ,mim.menu_item_master_id as mim_id
				        								  ,mim.id_tag
				        							FROM arsdehnel_core.menu_control_master mcm
				        							  INNER JOIN arsdehnel_core.menu_control_detail mcd ON mcd.menu_control_master_id = mcm.menu_control_master_id
				        							  INNER JOIN arsdehnel_core.menu_item_master mim ON mcd.menu_control_detail_id = mim.menu_control_detail_id
				        							WHERE mcm.site_id = $site_id
				        							  AND mcd.url = '/friends'
				        							  AND mcm.status_code = 'A'
				        							  AND mcd.status_code = 'A'
				        							  AND mim.status_code = 'A'
				        							  AND mcd.view_security_level <= $sec_level
				        							  AND mim.view_security_level <= $sec_level
				        							ORDER BY mim.sort_order");
		return $return;
    }

	function _common_item_master_breadcrumbs( $type_code, $item_master )
    {
    	$site_id	= $this->session->userdata('site_id');
    	$sec_level	= $this->session->userdata('security_level');
		$return['header_icon']	= "/images/subhead/$type_code.png";
		$return['help']			= "/help/$type_code";
		switch( $type_code ):
			case "project":
				$return['page_title']	= 'projects';
				$home_label				= 'projects home';
				$user_items_label		= 'my projects';
				$id_prefix				= 'projects';
				break;
			case "publication":
				$return['page_title']	= 'library';
				$home_label				= 'library home';
				$user_items_label		= 'my library';
				$id_prefix				= 'library';
				break;
			case "tool":
				$return['page_title']	= 'tool cabinet';
				$home_label				= 'tool cabinet home';
				$user_items_label		= 'my tools';
				$id_prefix				= 'tools';
				break;
			case "user_group":
				$return['page_title']	= 'groups';
				$home_label				= 'user groups home';
				$user_items_label		= 'my groups';
				$id_prefix				= 'groups';
				break;
		endswitch;
		$return['crumbs']['cuotadera home']	= "/base";
		$return['crumbs'][$home_label]		= "/browse/recent/$type_code";
		switch( $this->data['uri_data']['function'] ):
			case "recent":
				$return['crumbs'][$home_label]					= null;
				break;
			case "control":
				if( $this->data['uri_data']['browse_type'] == "all" ):
					$return['crumbs']['browse']					= null;
				else:
					$return['crumbs'][$user_items_label]		= null;
				endif;
				break;
			case "item_master":
				$return['crumbs']['create']						= null;
				break;
			case "view":
				switch( $this->session->userdata('breadcrumbid') ):
					case $id_prefix."-user":
						$return['crumbs'][$user_items_label]	= "/browse/control/$type_code/user";
						break;
					case $id_prefix."-browse":
						$return['crumbs']['browse']				= "/browse/control/$type_code/all";
						break;
				endswitch;
				$return['crumbs'][$item_master->item_title] = null;
				break;			
		endswitch;
		$return['subnav']		= $this->db->query("SELECT mim.display_text
				        								  ,mim.url
				        								  ,mim.page_title
				        								  ,mim.class
				        								  ,mim.menu_item_master_id as mim_id
				        								  ,mim.id_tag
				        							FROM arsdehnel_core.menu_control_master mcm
				        							  INNER JOIN arsdehnel_core.menu_control_detail mcd ON mcd.menu_control_master_id = mcm.menu_control_master_id
				        							  INNER JOIN arsdehnel_core.menu_item_master mim ON mcd.menu_control_detail_id = mim.menu_control_detail_id
				        							WHERE mcm.site_id = $site_id
				        							  AND mcd.url = '/browse/recent/$type_code'
				        							  AND mcm.status_code = 'A'
				        							  AND mcd.status_code = 'A'
				        							  AND mim.status_code = 'A'
				        							  AND mcd.view_security_level <= $sec_level
				        							  AND mim.view_security_level <= $sec_level
				        							ORDER BY mim.sort_order");
		return $return;
    }
    
}

/* End of file */
/* Location: ./system/application/models/menu_model.php */