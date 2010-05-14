<?php

class Bloguser extends DataMapper 
{

	var $has_one = array("blogmodel");
	var $has_many = array("blogpost");
	var $table = 'users';
	
	var $validation = array(
			array(
				'field' => 'username',
				'label' => 'Username',
				'rules' => array('required', 'trim', 'unique', 'alpha_dash', 'min_length' => 3)
				),
			array(
				'field' => 'password',
				'label' => 'Password',
				'rules' => array('required', 'trim', 'min_length' => 6, 'encrypt')
				),
			array(
			    'field' => 'confirm_password',
	            'label' => 'Confirm Password',
	            'rules' => array('encrypt', 'matches' => 'password'),
	        ),	
			array(
				'field' => 'firstname',
				'label' => 'First Name',
				'rules' => array('required', 'trim')
				),
			array(
				'field' => 'lastname',
				'label' => 'Last Name',
				'rules' => array('required', 'trim')
				),
			array(
				'field' => 'email',
				'label' => 'Email',
				'rules' => array('required', 'trim')
				),
			array(
				'field' => 'lastlogin',
				'label' => 'Last Login',
				'rules' => array('trim')
				),
			array(
				'field' => 'blogid',
				'label' => 'Blog ID',
				'rules' => array('required', 'trim', 'numeric')
				),
		);
	
	function Bloguser()
	{
		parent::DataMapper();
	}
}	
	
	
	// --------------------------------------------------------------------
	
/* End of file bloguser.php */
/* Location: ./application/models/bloguser.php */