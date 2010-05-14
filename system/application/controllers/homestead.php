<?php

class Homestead extends Controller
{
	public $data;
	function __construct()
	{
		parent::Controller();
		
		$this->data['ci_version'] = CI_VERSION;

		$this->load->database();
		$this->load->helper('url'); 
	}
	function index()
	{
		$this->newest();
	}
}

?>