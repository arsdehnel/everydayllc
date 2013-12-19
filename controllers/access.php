<?php

class Access extends CD_Controller {

	function __construct()
	{
		parent::__construct();	
		$this->load->model('Access_model');
	}//__construct
	
	function login()
	{
		$username 	= $this->input->post('username');
		$password	= $this->input->post('password');
		$data		= $this->Access_model->login( $username, $password );
       	$this->session->set_userdata($data);
		redirect($data['redirect_url']);
	}//function: login
	
	function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}//logout
	
	function request()
	{
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->form_validation->set_error_delimiters('<div id="modal_error_wrapper">', '</div>');		
		if ($this->form_validation->run() == FALSE):
			$this->load->view('access/request');
		else:
			$this->Access_model->create_request( $_POST );
		endif;
	}//request
	
	function note()
	{
		$this->load->view('access/'.$this->uri->segment(3));
	}
	
	function frgt_pwd()
	{
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->form_validation->set_error_delimiters('<div id="modal_error_wrapper">', '</div>');		
		if ($this->form_validation->run() == FALSE):
			$data['prcs_type']			= 'USRNME';
			$this->load->view('access/frgt_pwd', $data);
		else:
			$sec_data = $this->Access_model->retrieve_security_data( $_POST );
			if( $sec_data->num_rows() > 0 ):
				$row 					= $sec_data->row();
				redirect('access/rtrv_pwd/'.$row->user_id);
			else:
				$data['prcs_type']			= 'USRNME';
				$data['err_msg']			= "The username you provided does not exist in our system.";
				$this->load->view('access/frgt_pwd', $data);
			endif;
		endif;
	}
	
	function rtrv_pwd()
	{
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->form_validation->set_error_delimiters('<div id="modal_error_wrapper">', '</div>');		
		if ($this->form_validation->run() == FALSE):
			$sec_data = $this->Access_model->retrieve_security_data( $this->uri->segment(3) );
			$row 					= $sec_data->row();
			$data['user_id']		= $row->user_id;
			$data['sec_qstn']		= $row->sec_qstn;
			$data['username']		= $row->username;
			$data['prcs_type']		= 'SEC_QSTN';
			$this->load->view('access/rtrv_pwd', $data );
		else:
			$sec_data = $this->Access_model->retrieve_security_data( $_POST );
			if( $sec_data->num_rows() > 0 ):
				$row				= $sec_data->row();
				if( trim( strtoupper( $row->sec_ansr ) ) == trim( strtoupper( $_POST['sec_ansr'] ) ) ):
			    	$this->load->helper('email');
					$view_data['password']		= $row->password;
					$view_data['content']		= $this->load->view('access/frgt_pwd_email.php', $view_data, true );

					$email_data['to_address']	= $row->email_address;
					$email_data['from_address']	= 'newaccounts@cuotadera.net';
					$email_data['subject']		= '[ cuotadera ] Password Retrieval';
					$email_data['message']		= $this->load->view('common/email_template.php', $view_data, true );
					
					if( ! send_email( $email_data ) ):
						echo 'problem';
					else:
						$this->session->set_userdata('success_line','Yay! Request processed and sent to the e-mail address provided!');
					endif;
				else:
					$data['user_id']		= $row->user_id;
					$data['sec_qstn']		= $row->sec_qstn;
					$data['username']		= $row->username;
					$data['prcs_type']		= 'SEC_QSTN';
					$data['err_msg']		= "The security answer you provided is incorrect.";
					$this->load->view('access/rtrv_pwd', $data );					
				endif;
			endif;
		endif;
	}
	
	function confirm()
	{
		$this->load->model('Form_model');
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
		$this->form_validation->set_error_delimiters('<div id="modal_error_wrapper">', '</div>');		
		if ($this->form_validation->run() == FALSE):
			if( $this->uri->segment(3) == '' ):
				$data['request_key']	= $this->input->post('request_key');
			else:
				$data['request_key']	= $this->uri->segment(3);
			endif;
			$data['request']		= $this->Access_model->retrieve_request( $data );
			$data['sec_qstn'] 		= $this->Form_model->retrieve_domain_group_value_list( 'pub_inpt_sec_qstn', 'retrv_acct_info' );
			$content				= $this->load->view('access/confirm', $data, true );			
			$this->data['content']			= $content;
			$this->load->view('layout/main', $this->data);
		else:
			$this->data['login_form'] 		= $this->load->view('access/login', null, true);
			$this->data['content']			= $this->load->view('access/confirmed', $this->data, true);
			$this->load->view('layout/main', $this->data);
		endif;
	}
	
}//class: access