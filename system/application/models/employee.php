<?php

/**
 * Employee Class (contains self referencing relationships)
 *
 * Transforms employees table into a Employee object.
 * This is just here for use with the example in the Controllers.
 *
 * @licence 	MIT Licence
 * @category	Models
 * @author  	Simon Stenhouse
 * @link    	http://stensi.com
 */
class Employee extends DataMapper
{
	var $table = "employees";

	var $validation = array(
		array(
			'field' => 'first_name',
			'label' => 'First Name',
			'rules' => array('required', 'trim', 'alpha', 'max_length' => '40', 'unique_pair' => 'last_name')
		),
		array(
			'field' => 'last_name',
			'label' => 'Last Name',
			'rules' => array('required', 'trim', 'alpha', 'max_length' => '40')
		),
		array(
			'field' => 'position',
			'label' => 'Position',
			'rules' => array('required', 'valid_match' => array('Manager', 'Supervisor', 'Underling'))
		),
		array(
			'field' => 'manager',
			'label' => 'Managers',
			'rules' => array('max_size' => 1)
		),
		array(
			'field' => 'supervisor',
			'label' => 'Supervisor',
			'rules' => array('max_size' => 2)
		),
		array(
			'field' => 'underling',
			'label' => 'Underling',
			'rules' => array('max_size' => 4)
		)		
	);

	/**
	 * Constructor
	 *
	 * Initialize DataMapper.
	 */
	function Employee()
	{
		parent::DataMapper();
	}

	// --------------------------------------------------------------------
	
	/**
	 * Full Name
	 *
	 * Returns the employees full name.
	 *
	 * @access	public
	 * @return	string
	 */
	function full_name()
	{
		return $this->first_name . ' ' . $this->last_name;
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
		// Overload this in your inheriting models
	}

	// --------------------------------------------------------------------


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 *                                                                   *
	 * Overload methods                                                  *
	 *                                                                   *
	 * The following are methods that overload the default               *
	 * functionality of DataMapper.                                      *
	 *                                                                   *
	 * It is necessary for self referencing relationships.               *
	 *                                                                   *
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


	// --------------------------------------------------------------------

	/**
	 * Get (overload)
	 *
	 * Get objects.
	 *
	 * @access	public
	 * @param	int or array
	 * @return	bool
	 */
	function get($limit = NULL, $offset = NULL)
	{
		$this->_prepare_for_query();

		return parent::get($limit, $offset);
	}

	// --------------------------------------------------------------------

	/**
	 * Count (overload)
	 *
	 * Returns the total count of the objects records.
	 * If on a related object, returns the total count of related objects records.
	 *
	 * @access	public
	 * @param	int or array
	 * @return	bool
	 */
	function count()
	{
		if (empty($this->parent))
		{
			$this->_prepare_for_query();
		}

		return parent::count();
	}
}

/* End of file employee.php */
/* Location: ./application/models/employee.php */