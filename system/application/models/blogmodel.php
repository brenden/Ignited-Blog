<?php

class Blogmodel extends DataMapper 
{

	var $has_one = array("bloguser");
	var $has_many = array("blogpost");
	var $table = 'blogs';
	
	function Blogmodel()
	{
		parent::DataMapper();
	}
}	
	
	
	// --------------------------------------------------------------------
	
/* End of file blogmodel.php */
/* Location: ./application/models/blogmodel.php */