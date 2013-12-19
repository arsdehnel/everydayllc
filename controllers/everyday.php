<?php

class Everyday extends ED_Controller {

	function __construct()
	{
		parent::__construct();
	}//__construct   
	
	function index()
	{
		$this->data['page_title']		= 'Home';
		$this->load->view('layout/main', $this->data);
	}//index
	
	function log_viewer()
	{
		$this->load->helper('file');
		echo '<pre>';
		echo read_file('./logs/log-'.date("Y-m-d").'.php');
		echo '</pre>';
	}
	
	function log_reset()
	{
		$this->load->helper('file');
		delete_files(APPPATH.'logs/');
		$default_403_forbidden = file_get_contents(APPPATH.'index.html');
		write_file(APPPATH.'logs/index.html', $default_403_forbidden);
	}
	
}