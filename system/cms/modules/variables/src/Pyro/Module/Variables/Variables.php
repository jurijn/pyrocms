<?php namespace Pyro\Module\Variables;

/**
 * Variable Library
 *
 * Handles the variables data
 *
 * @author		PyroCMS Dev Team
 * @package  	PyroCMS\Core\Modules\Variables\Libraries
 */
class Variables {

	private $_vars = null;

	// ------------------------------------------------------------------------

	/**
	 * Magic get
	 *
	 * Used to pull out a variable's data
	 *
	 * @param	string
	 * @return 	mixed
	 */
	public function __get($name)
	{
		// Variables are being used on this site and they 
		// haven't been loaded yet... now eager load them
		if ($this->_vars === null)
		{
			$this->get_all();
		}

		// the requested variable isn't in the database or cache; set it to null
		if ( ! isset($this->_vars[$name]))
		{
			$this->_vars[$name] = null;
		}

		return $this->_vars[$name];
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Magic set
	 *
	 * Used to set a variable's data
	 *
	 * @param	string
	 * @return 	mixed
	 */
	public function __set($name, $value)
	{
		// if $this->_vars is null then load them all as this is 
		// the first time this library has been touched
		if ($this->_vars === null)
		{
			$this->get_all();
		}

		$this->_vars[$name] = $value;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get all
	 *
	 * Get an array of all the vars
	 *
	 * @return array
	 */
	public function get_all()
	{
		// the variables haven't been fetched yet, load them
		if ($this->_vars === null)
		{
			$this->_vars = array();

			if ( ! ($cached_vars = ci()->cache->get('variables_library_vars')))
			{
				$entries = VariableModel::all(array('name', 'data'));

				foreach ($entries as $var)
				{
					$this->_vars[$var['name']] = $var['data'];
				}
			} 
			else
			{
				$this->_vars = $cached_vars;
			}
		}

		return ci()->cache->set('variables_library_vars', $this->_vars);
	}
}

/* End of file Variables.php */