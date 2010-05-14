<?php

/**
 * Underling Class (contains self referencing relationships)
 *
 * Transforms employees table into a Underling object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 */
class Underling extends Employee
{
	var $has_one = array("supervisor");

	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function Underling()
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
		$position = empty($this->position) ? 'Underling' : $this->position;

		$this->where('position', $position);
	}
}

/* End of file underling.php */
/* Location: ./application/models/underling.php */
