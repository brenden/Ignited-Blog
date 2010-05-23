<?php

class Blogtag extends DataMapper 
{
	var $table = 'tags';
	
	var $validation = array(
			array(
				'field' => 'post_id',
				'label' => 'Post ID',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'tag',
				'label' => 'Tag',
				'rules' => array('required')
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
/* End of file blogtag.php */
/* Location: ./application/models/blogtag.php */