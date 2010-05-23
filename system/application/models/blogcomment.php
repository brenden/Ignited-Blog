<?php

class Blogcomment extends DataMapper 
{
	var $has_one = array("blogpost");
	var $table = 'comments';
	
	var $validation = array(
			array(
				'field' => 'post_id',
				'label' => 'Post ID',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'blog_id',
				'label' => 'Blog ID',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'author',
				'label' => 'Name',
				'rules' => array('required')
				),
			array(
				'field' => 'email',
				'label' => 'Email Address',
				'rules' => array('required')
				),
			array(
				'field' => 'website',
				'label' => 'Website',
				'rules' => array()
				),
			array(
				'field' => 'ip',
				'label' => 'IP',
				'rules' => array('required')
				),
			array(
				'field' => 'date',
				'label' => 'Date',
				'rules' => array('required')
				),
			array(
				'field' => 'body',
				'label' => 'Body',
				'rules' => array('required')
				),
			array(
				'field' => 'flagged',
				'label' => 'Flagged',
				'rules' => array('required')
				)
		);
	function Blogcomment()
	{
		parent::DataMapper();
	}
}	
	
	
	// --------------------------------------------------------------------
	
/* End of file blogcomment.php */
/* Location: ./application/models/blogcomment.php */