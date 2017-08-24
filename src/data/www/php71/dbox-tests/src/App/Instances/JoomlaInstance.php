<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances;

use Joomla\Database\DatabaseDriver;

/**
 * Class JoomlaInstance.
 *
 * @property   integer  $instance_id       PK
 * @property   integer  $php_version       PHP version
 * @property   integer  $user_id           Github user ID
 * @property   integer  $pr_id             Pull Request ID
 * @property   string   $requested_at      Date and time when instance was requested
 * @property   string   $target_branch     PR target branch
 *
 * @since __DEPLOY_VERSION__
 */
class JoomlaInstance implements \Serializable
{
    /**
    * Primary Key
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $instance_id = 0;

    /**
    * Github User ID
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $user_id;

    /**
    * PHP version
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $php_version;

    /**
    * PR ID
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $pr_id;

	/**
	 * Date and time of requested instance
	 *
	 * @var    string
	 * @since __DEPLOY_VERSION__
	 */
	protected $requested_at;

	/**
	 * PR target branch
	 *
	 * @var    string
	 * @since __DEPLOY_VERSION__
	 */
	protected $target_branch;

    /**
    * @var    DatabaseDriver
    * @since __DEPLOY_VERSION__
    */
    private $database = null;

    /**
    * Constructor.
    *
    * @param   DatabaseDriver  $database  The database connector.
    * @param   object          $data      The project data.
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function __construct(DatabaseDriver $database, $data = null)
	{
		$this->setDatabase($database);

		if (is_null($data))
		{
			return;
		}

		foreach ($data as $key => $value)
		{
			if (isset($this->$key) || is_null($this->$key))
			{
				$this->$key = $value;

				continue;
			}

			throw new \UnexpectedValueException(__METHOD__ . ' - unexpected key: ' . $key);
		}
	}

    /**
    * Get a value.
    *
    * @param   string  $key  The key name
    *
    * @return  mixed
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __get($key)
	{
		if (isset($this->$key))
		{
			return $this->$key;
		}

		return 'not set..';
	}


    /**
    * Get the project id.
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getInstance_Id()
	{
		return $this->instance_id;
	}

    /**
    * Get the Github User ID.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getUser_Id()
	{
		return $this->user_id;
	}

    /**
    * Get the PR id.
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getPR_Id()
	{
		return $this->pr_id;
	}

    /**
    * Get the PHP version.
    *
    * @return  integer
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getPHP_Version()
	{
		return $this->php_version;
	}

	/**
	 * Get the request date and time.
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getRequested_At()
	{
		return $this->requested_at;
	}

	/**
	 * Get the target branch
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getTarget_Branch()
	{
		return $this->target_branch;
	}

    /**
    * Method to set the database connector.
    *
    * @param   DatabaseDriver  $database  The database connector.
    *
    * @return  void
    *
    * @since 1.0
    */
    public function setDatabase(DatabaseDriver $database)
	{
		$this->database = $database;
	}

    /**
    * String representation of object
    *
    * @return  string  The string representation of the object or null
    *
    * @link    http://php.net/manual/en/serializable.serialize.php
    * @since   __DEPLOY_VERSION__
    */
    public function serialize()
	{
		$props = [];

		foreach (get_object_vars($this) as $key => $value)
		{
			if (in_array($key, ['authModel', 'cleared', 'authId', 'database']))
			{
				continue;
			}

			$props[$key] = $value;
		}

		return serialize($props);
	}

    /**
    * Constructs the object
    *
    * @param   string  $serialized  The string representation of the object.
    *
    * @return  void
    *
    * @link    http://php.net/manual/en/serializable.unserialize.php
    * @since   __DEPLOY_VERSION__
    */
    public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		foreach ($data as $key => $value)
		{
			$this->$key = $value;
		}
	}

}
