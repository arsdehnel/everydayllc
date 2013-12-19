<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Access_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
	
	function login( $username, $password )
    {
    	$site_id		= $this->session->userdata('site_id');
		$login_query	= $this->db->query("SELECT u.user_id,
												 u.username,
												 u.password,
												 su.security_level
											FROM arsdehnel_core.users u
												,arsdehnel_core.site_users su
											WHERE u.user_id = su.user_id
												AND su.site_id = $site_id
												AND u.username = '$username'" );
		if( !$login_query->num_rows() > 0 ){
				$data 	= array( 'redirect_url'		=> '/'
							  	,'error_line' 		=> "The username provided ($username) was not found in the database."
	        				 );        	
		}else{
			$login_data = $login_query->row();

			if( $login_data->password == md5($password) ){
				$data 	= array( 'redirect_url'		=> $this->session->userdata('login_url') ? $this->session->userdata('login_url') : '/base'
								,'secure_ind'		=> TRUE
								,'username'			=> $login_data->username
							 	,'user_id'			=> $login_data->user_id
							 	,'security_level' 	=> $login_data->security_level
							 	,'success_line' 	=> 'Login successful.'
	        				 );        	
			}else{
				$data 	= array( 'redirect_url'		=> '/'
							 	,'error_line' 		=> 'The password did not match the password for the given username in the database.'
	        				 );        	
			}
		}
		return $data;
    }
    
    function security_check( $security_level )
    {
    	$this->load->helper('url');
    	$user_security_level = $this->session->userdata('security_level');
    	if( $security_level > $user_security_level ):
    		$this->session->set_userdata('login_url', current_url() );
    		$this->session->set_userdata('alert_header','Security Violoation');
    		$this->session->set_userdata('alert_text','The page you are attempting to access required a higher security level than your session currently has.');
    		$this->session->set_userdata('alert_type','warning');
    		redirect('/');
    	endif;
    }

    function create_request( $data )
    {
    	$this->load->helper('email');
    	extract( $data );
		$site_id	= $this->session->userdata('site_id');
		$query		= $this->db->query("SELECT request_key
											,status_code
										FROM arsdehnel_core.user_account_request_detail
										WHERE site_id = $site_id
										  AND upper(email_address) = upper('$email_address')");
		if( $query->num_rows() == 0 ):
			$user_id	= $this->session->userdata('user_id');
			$request_key 		= random_string( 'alnum', 32 );
			$query				= $this->db->query("INSERT INTO arsdehnel_core.user_account_request_detail (
														user_account_request_detail_id
														,site_id
														,request_key
														,email_address
														,status_code
														,created_by
														,created_date
													) VALUES (
														nextval('arsdehnel_core.user_account_request_detail_id_seq')
														,$site_id
														,'$request_key'
														,'$email_address'
														,'R'
														,$user_id
														,'".date(DATE_ISO8601)."'
													)");
			$view_data['request_key']	= $request_key;
			$view_data['base_url']		= $this->config->site_url();
			$view_data['content']		= $this->load->view('access/request_email.php', $view_data, true );

			if( ENVIRONMENT == "development" ):
				$email_data['to_address']	= "arsdehnel@gmail.com";
			else:
				$email_data['to_address']	= $email_address;
			endif;
			$email_data['from_address']	= 'newaccounts@cuotadera.net';
			$email_data['subject']		= '[ cuotadera ] New Account Request';
			$email_data['message']		= $this->load->view('common/email_template.php', $view_data, true );
			
			if( ! send_email( $email_data ) ):
				echo 'problem';
			else:
				$this->session->set_userdata('success_line', 'Your account request has been successfully submitted!  You should receive an e-mail shortly with details about activating your account.');
			endif;
		else:
			$request = $query->row();
			if( $request->status_code == 'R' ):
				$view_data['request_key']	= $request->request_key;
				$view_data['base_url']		= $this->config->site_url();
				$view_data['content']		= $this->load->view('access/request_email.php', $view_data, true );

				$email_data['to_address']	= $email_address;
				$email_data['from_address']	= 'newaccounts@cuotadera.net';
				$email_data['subject']		= '[ cuotadera ] New Account Request';
				$email_data['message']		= $this->load->view('common/email_template.php', $view_data, true );
				
				if( ! send_email( $email_data ) ):
					echo 'problem';
				else:
					$this->session->set_userdata('success_line','We already have that e-mail address associated with a user, but since it hasn\'t been activated yet we sent you another activation e-mail.');
				endif;
			else:
				echo 'That e-mail address already has an account setup.  Is it you? If you have forgotten your password try the \'forgot password\' link to retrieve your username and password';
			endif;
		endif;
    }
    
	function retrieve_security_data( $data )
	{
		$site_id	= $this->session->userdata('site_id');
		if( is_array( $data ) ):
			extract($data);
			$query		= $this->db->query("SELECT u.username
												,u.user_id
												,u.sec_qstn
												,u.sec_ansr
												,u.password
												,ue.email_address
										  	FROM arsdehnel_core.users u
										    	,arsdehnel_core.site_users su
										    	,arsdehnel_core.user_email ue
										  	WHERE u.user_id = su.user_id
										  	    AND u.user_id = ue.user_id
										    	AND u.username = '$username'
										    	AND su.site_id = $site_id" );	
		else:
			$query		= $this->db->query("SELECT u.username
												,u.user_id
												,u.sec_qstn
												,u.sec_ansr
												,u.password
												,ue.email_address
										  	FROM arsdehnel_core.users u
										    	,arsdehnel_core.site_users su
										    	,arsdehnel_core.user_email ue
										  	WHERE u.user_id = su.user_id
										  	    AND u.user_id = ue.user_id
										    	AND u.user_id = $data
										    	AND su.site_id = $site_id" );
		endif;
		return $query;		
	}
	
    function retrieve_request( $data )
    {
    	$site_id	= $this->session->userdata('site_id');
    	extract($data);
    	$query		= $this->db->query("SELECT email_address
											,status_code
											,request_key
										FROM arsdehnel_core.user_account_request_detail
										WHERE site_id = $site_id
											AND request_key = '$request_key'");
		return $query;
    }
    
    function save_request( $data )
    {
    	$site_id	= $this->session->userdata('site_id');
    	extract( $data );
    	$query		= $this->db->query("SELECT user_account_request_detail_id
    									FROM arsdehnel_core.user_account_request_detail
    									WHERE site_id = $site_id
    									  AND request_key = '$request_key'");
    	if( $query->num_rows() == 0 ):
    		$this->session->set_userdata('error_line', 'The request key for your account could not be confirmed.  Please try the link again or contact the webmaster if you continue to have trouble.');
    		return;
    	else:
    		$row	= $query->row();
    		$this->db->set(array('status_code' => 'C', 'modified_by' => $this->session->userdata('user_id'), 'modified_date' => date(DATE_ISO8601) ) );
    		$this->db->where('user_account_request_detail_id', $row->user_account_request_detail_id );
    		$this->db->update('arsdehnel_core.user_account_request_detail');
    	endif;
    	$query		= $this->db->query("SELECT user_id
    									FROM arsdehnel_core.user_email
    									WHERE email_address = '$email_address'");
    	if( $query->num_rows() > 0 ):
			$this->session->set_userdata('error_line', 'The e-mail address you have provided is already associated with another user.');			    		
			return;
    	endif;
    	unset($data['request_key'], $data['email_address'], $data['password_hidden'], $data['password_text']);
    	$data['password'] = md5($data['password']);
		$this->db->set($data);
		$this->db->set(array('status_code' => 'A', 'created_by' => $this->session->userdata('user_id'), 'created_date' => date(DATE_ISO8601) ) );
		$this->db->insert('arsdehnel_core.users');
		$user_id = $this->db->insert_id();
		echo 'test';
		$this->db->set(array('user_id' => $user_id, 'email_address' => $email_address, 'email_type_code' => 'M', 'status_code' => 'A', 'created_by' => $this->session->userdata('user_id'), 'created_date' => date(DATE_ISO8601) ) );
		$this->db->insert('arsdehnel_core.user_email');
		$this->db->set(array('user_id' => $user_id, 'site_id' => $site_id, 'security_level' => 1, 'status_code' => 'A', 'created_by' => $this->session->userdata('user_id'), 'created_date' => date(DATE_ISO8601) ) );
		$this->db->insert('arsdehnel_core.site_users');
		$this->db->set(array('user_account_request_detail_id'=>$row->user_account_request_detail_id, 'request_user_id'=>$user_id, 'modified_by' => $this->session->userdata('user_id'), 'modified_date' => date(DATE_ISO8601) ));
		$this->db->update('arsdehnel_core.user_account_request_detail');
		echo 'test2';
		$this->session->set_userdata('alert_header','Success!');
		$this->session->set_userdata('alert_text','Profile confirmed!  Please login and you should be all set!');
		$this->session->set_userdata('alert_type','success');
    }

}

/* End of file */
/* Location: ./system/application/models/access_model.php */