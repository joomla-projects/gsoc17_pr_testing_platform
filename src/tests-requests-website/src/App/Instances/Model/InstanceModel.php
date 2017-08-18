<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\Model;

use App\Instances\Table\InstancesTable;
use App\Instances\JoomlaInstance;

use Joomla\Filter\InputFilter;
use Joomla\Date\Date;
use JTracker\Model\AbstractTrackerDatabaseModel;

/**
 * Model to get data for the instance list view
 *
 * @since __DEPLOY_VERSION__
 */
class InstanceModel extends AbstractTrackerDatabaseModel
{
    /**
     * Get an item.
     *
     * @param integer $instanceId The instance id.
     *
     * @return JoomlaInstance
     *
     * @since __DEPLOY_VERSION__
     */
    public function getItem($instanceId = null)
    {
		if (is_null($instanceId))
		{
			$app = $this->getContainer()->get('app');
			$instanceId = $app->input->get('instance_id', 1);
		}

		$data = $this->db->setQuery(
			$this->db->getQuery(true)
				->from($this->db->quoteName('#__instances', 'i'))
				->select('i.*')
				->where($this->db->quoteName('i.instance_id') . ' = ' . (int) $instanceId)
		)->loadObject();

		if (!$data)
		{
			throw new \UnexpectedValueException('This Joomla Instance does not exist.', 404);
		}

		return new JoomlaInstance($this->db, $data);
	}


    /**
    * Method to get an instance by its id.
    *
    * @param   integer  $instance_id  The instance ID.
    *
    * @return  JoomlaInstance
    *
    * @since  __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function getByInstanceID($instance_id = null)
	{
		if (!$instance_id)
		{
			return new JoomlaInstance($this->db);
		}

		$data = $this->db->setQuery(
			$this->db->getQuery(true)
				->from($this->db->quoteName('#__instances', 'i'))
				->select('i.*')
				->where($this->db->quoteName('i.instance_id') . ' = ' . $this->db->quote($instance_id))
		)->loadObject();

		if (!$data)
		{
			throw new \UnexpectedValueException('This Joomla Instance does not exist.', 404);
		}

		return new JoomlaInstance($this->db, $data);

	}

    /**
    * Delete a Joomla! instance.
    *
    * @param   integer  $instance_id  The instance ID.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function delete($instance_id)
	{
		// Remove the joomla instance
		$db    = $this->getDb();
		$query = $db->getQuery(true);

		$db->setQuery(
			$query->delete('#__instances')
				->where('instance_id' . '=' . $instance_id)
		)->execute();

		$php_version = substr($instance_id, 0, 2);
		$removeInstanceCmdArgs = $php_version . " " . $instance_id;

		$container_name = "php" . $php_version;

		$command = "docker exec --user root " . $container_name . " /bin/sh -c \"cd shared; ./files/remove_instance.sh " . $removeInstanceCmdArgs . ";\"";

		$output = [];
		$return = null;
		exec($command, $output, $return);

		//throw new \RuntimeException(implode("\n", $output) . "\n\nCommand returned $return\n");

		// Delete the category from the table
		(new InstancesTable($db))->delete($instance_id);

		return $this;
	}


	/**
	 * Adds a new Joomla! instance
	 *
	 * @param   array  $src  The source
	 *
	 * @return  $this  This allows chaining
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function add(array $src)
	{
		$filter = new InputFilter;

		$instanceID = $src['php_version'] . $src['user_id'] . $src['pr_id'];

		$db = $this->db;

		$data = [
			'instance_id'  => $filter->clean($instanceID, 'uint'),
			'php_version'  => $filter->clean($src['php_version'], 'uint'),
			'user_id'      => $filter->clean($src['user_id'], 'uint'),
			'pr_id'        => $filter->clean($src['pr_id'], 'uint'),
			'requested_at' => $db->quote((new Date)->format($this->getDb()->getDateFormat())),
		];

		if ($data['instance_id'] == null)
		{
			throw new \RuntimeException('Missing ID');
		}

		if ($data['php_version'] == null)
		{
			throw new \RuntimeException('Please select a PHP version for the Joomla! instance.');

		}

		$query = $db->getQuery(true);

		$query->select('*');

		$query->from($db->quoteName('#__instances', 'i'));

		$query->where($db->quoteName('i.user_id') . ' = ' . $data['user_id']);

		$numRows = $this->db->getNumRows($this->db->setQuery($query)->execute());

		$query->where($db->quoteName('i.instance_id') . ' = ' . $data['instance_id']);

		$instanceExists = $this->db->getNumRows($this->db->setQuery($query)->execute());


		if (!$instanceExists)
		{
			if ($numRows < 5)
			{
				$this->db->setQuery(
					$this->db->getQuery(true)
						->insert($this->db->quoteName('#__instances'))
						->columns(array_keys($data))
						->values(implode(', ', $data))
				)->execute();

				$addInstanceCmdArgs = $data['pr_id'] . " " . $data['php_version'] . " " . $data['instance_id'];

				$output = [];
				$return = null;

				$container_name = "php" . $data['php_version'];

				$command = "docker exec --user root " . $container_name . " /bin/sh -c \"cd shared; ./files/add_instance.sh " . $addInstanceCmdArgs . ";\"";

				exec($command, $output, $return);

				//throw new \RuntimeException(implode("\n", $output) . "\n\nCommand returned $return\n");
			}
			else{
				throw new \RuntimeException('Maximum number of active Joomla! instances exceeded!');
			}
		}
		else{
			throw new \RuntimeException("Joomla Instance #" . $data['instance_id'] . " is already active!");
		}

		return $this;
	}
}
