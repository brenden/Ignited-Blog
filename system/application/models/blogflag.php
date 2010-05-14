<?php

class Blogflag extends DataMapper 
{
	var $table = 'flags';
	
	var $validation = array(
			array(
				'field' => 'commentid',
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
		$this->commentid = $id;
		$this->ip = $ip;
		
		$this->where('commentid', $id);
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