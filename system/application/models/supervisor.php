<?php

/**
 * Supervisor Class (contains self referencing relationships)
 *
 * Transforms employees table into a Supervisor object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 */
class Supervisor extends Employee
{
	var $has_one = array("manager");
	var $has_many = array("underling");

	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function Supervisor()
	{
		parent::Employee();
	}

	// --------------------------------------------------------------------

	/**
	 * Prepare For Query
	 *
	 * Prepares the necessary pre-query conditions for this object.
	 *
	 * @access	private
	 * @return	void
	 */
	function _prepare_for_query()
	{
		$position = empty($this->position) ? 'Supervisor' : $this->position;

		$this->where('position', $position);
	}
}

/* End of file supervisor.php */
/* Location: ./application/models/supervisor.php */