<?php

class Blogpost extends DataMapper 
{
	var $has_one = array("bloguser");
	var $has_many = array("blogcomment");
	var $table = 'posts';
	
	var $validation = array(
			array(
				'field' => 'blog_id',
				'label' => 'Blog ID',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'user_id',
				'label' => 'User',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'title',
				'label' => 'Title',
				'rules' => array('required')
				),
			array(
				'field' => 'body',
				'label' => 'Content',
				'rules' => array('required')
				),
			array(
				'field' => 'date',
				'label' => 'Date',
				'rules' => array('required')
				),
			array(
				'field' => 'tags',
				'label' => 'Tags',
				'rules' => array()
				),
			array(
				'field' => 'commentlock',
				'label' => 'Comments Locked',
				'rules' => array()
				),
			array(
				'field' => 'deleted',
				'label' => 'Deleted',
				'rules' => array()
				)
		);
	function Blogpost()
	{
		parent::DataMapper();
	}
	
	function exists($id)
	{
		$this->id = $id;
		$this->where("id = '" . $id . "'");
		if($this->count() > 0)
		{
			return(true);
		}
		return(false);
	}
}
	
	
	// --------------------------------------------------------------------
	
/* End of file blogpost.php */
/* Location: ./application/models/blogpost.php */