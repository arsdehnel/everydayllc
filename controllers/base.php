<?php

class Base extends CD_Controller {

	function __construct()
	{
		parent::__construct();	
		$this->Access_model->security_check(1);
	}//__construct   
	
	function index()
	{
		$this->data['page_title']		= 'Secure homepage';
		$this->data['content']			= 'Secure content';
		$this->load->view('layout/main', $this->data);
	}//index
}