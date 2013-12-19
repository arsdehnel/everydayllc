<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ED_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if( !$this->session->userdata('site_id') ){
        	$data = array(
        				  'site_id' 		=> 24,
        				  'user_id' 		=> 0,
        				  'security_level'	=> 0
        				 );        	
        	$this->session->set_userdata($data);
        }
		$this->uri->segments_to_array();
        $menu['nav']				= $this->Layout_model->retrieve_menu( 'main_menu' );
        $this->data['nav']			= $this->load->view('layout/menu', $menu, true);
        $footer['nav']				= $this->Layout_model->retrieve_menu( 'footer_menu' );
        $this->data['footer']		= $this->load->view('layout/footer', $footer, true);
        $this->data['header']		= $this->load->view('layout/header', null, true);
        $breadcrumbs				= $this->Layout_model->retrieve_breadcrumbs();
        $this->data['breadcrumbs']	= $this->load->view('layout/breadcrumbs', $breadcrumbs, true);
    }
    
}


/* End of file welcome.php */
/* Location: ./application/core/CD_Controller.php */