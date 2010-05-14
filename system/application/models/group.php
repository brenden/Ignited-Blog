<?php

/**
 * Group Class
 *
 * Transforms groups table into an object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 */
class Group extends DataMapper {

	var $has_many = array("user");	
	
	var $validation = array(
		array(
			'field' => 'name',
			'label' => 'Name',
			'rules' => array('required', 'trim', 'unique', 'min_length' => 3, 'max_length' => 20)
		),
		array(
			'field' => 'user',
			'label' => 'User',
			'rules' => array('max_size' => 5) // Each group can have no more than 5 users
		)
	);
	
	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function Group()
	{
		parent::DataMapper();
	}
}

/* End of file group.php */
/* Location: ./application/models/group.php */