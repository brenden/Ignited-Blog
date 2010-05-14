<?php

/**
 * Manager Class (contains self referencing relationships)
 *
 * Transforms employees table into a Manager object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 */
class Manager extends Employee
{
	var $has_many = array("supervisor");

	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function Manager()
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
		$position = empty($this->position) ? 'Manager' : $this->position;

		$this->where('position', $position);
	}
}

/* End of file manager.php */
/* Location: ./application/models/manager.php */