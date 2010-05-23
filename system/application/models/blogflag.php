<?php

class Blogflag extends DataMapper 
{
	var $table = 'flags';
	
	var $validation = array(
			array(
				'field' => 'comment_id',
				'label' => 'Comment ID',
				'rules' => array('required', 'numeric')
				),
			array(
				'field' => 'ip',
				'label' => 'IP',
				'rules' => array('required')
				)
		);
	function Blogcomment()
	{
		parent::DataMapper();
	}
	
	function exists($id, $ip)
	{
		$this->comment_id = $id;
		$this->ip = $ip;
		
		$this->where('comment_id', $id);
		$this->where('ip', $ip);
		
		if($this->count() > 0)
		{
			return(true);
		}
		return(false);
	}
}	
	
	
	// --------------------------------------------------------------------
	
/* End of file blogcomment.php */
/* Location: ./application/models/blogcomment.php */