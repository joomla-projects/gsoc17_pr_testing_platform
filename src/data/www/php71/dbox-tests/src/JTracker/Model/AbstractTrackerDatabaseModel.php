<?php
/**
 * Part of the Joomla Tracker Model Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Model;

use App\Projects\ProjectAwareTrait;

use Joomla\Model\AbstractDatabaseModel;
use Joomla\Database\DatabaseDriver;

use JTracker\Database\AbstractDatabaseTable;

/**
 * Abstract base model for the tracker application
 *
 * @since __DEPLOY_VERSION__
 */
abstract class AbstractTrackerDatabaseModel extends AbstractDatabaseModel
{
    use ProjectAwareTrait;

    /**
    * The model (base) name
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $name = null;

    /**
    * The URL option for the component.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $option = null;

    /**
    * Table instance
    *
    * @var    AbstractDatabaseTable
    * @since __DEPLOY_VERSION__
    */
    protected $table;

    /**
    * Instantiate the model.
    *
    * @param   DatabaseDriver  $database  The database adapter.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(DatabaseDriver $database)
	{
		parent::__construct($database);

		// Guess the option from the class name (Option)Model(View).
		if (empty($this->option))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Strip the base component namespace off
			$className = str_replace('App\\', '', $fqcn);

			// Explode the remaining name into an array
			$classArray = explode('\\', $className);

			// Set the option as the first object in this array
			$this->option = $classArray[0];
		}

		// Set the view name
		if (empty($this->name))
		{
			$this->getName();
		}
	}

    /**
    * Method to get the model name
    *
    * The model name. By default parsed using the class name or it can be set
    * by passing a $config['name'] in the class constructor
    *
    * @return  string  The name of the model
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getName()
	{
		if (empty($this->name))
		{
			// Get the fully qualified class name for the current object
			$fqcn = (get_class($this));

			// Explode the name into an array
			$classArray = explode('\\', $fqcn);

			// Get the last element from the array
			$class = array_pop($classArray);

			// Remove Model from the name and store it
			$this->name = str_replace('Model', '', $class);
		}

		return $this->name;
	}

    /**
    * Method to get a table object, load it if necessary.
    *
    * @param   string  $name    The table name. Optional.
    * @param   string  $prefix  The class prefix. Optional.
    *
    * @return  AbstractDatabaseTable  A Table object
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function getTable($name = '', $prefix = 'Table')
	{
		if (empty($name))
		{
			$name = $this->getName();
		}

		$class = $prefix . ucfirst($name);

		if (!class_exists($class) && !($class instanceof AbstractDatabaseTable))
		{
			throw new \RuntimeException(sprintf('Table class %s not found or is not an instance of AbstractDatabaseTable', $class));
		}

		$this->table = new $class($this->getDb());

		return $this->table;
	}
}
