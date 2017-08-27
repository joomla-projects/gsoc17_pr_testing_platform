<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances\Model;

use Joomla\Database\DatabaseQuery;

use JTracker\Authentication\GitHub\GitHubUser;
use JTracker\Model\AbstractTrackerListModel;

/**
 * Model to get data for the instances list view
 *
 * @since __DEPLOY_VERSION__
 */
class InstancesModel extends AbstractTrackerListModel
{
    /**
    * User object
    *
    * @var    GitHubUser
    * @since __DEPLOY_VERSION__
    */
    protected $user = null;

    /**
    * Get a user object.
    *
    * @return  GitHubUser
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function getUser()
	{
		if (is_null($this->user))
		{
			throw new \RuntimeException('User not set.');
		}

		return $this->user;
	}

    /**
    * Set the user object.
    *
    * @param   GitHubUser  $user  The user object.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setUser(GitHubUser $user)
	{
		$this->user = $user;

		return $this;
	}

    /**
    * Method to get a DatabaseQuery object for retrieving the data set from a database.
    *
    * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function getListQuery()
	{
		$db = $this->getDb();

		$query = $db->getQuery(true);

		$query->select('*');

		$query->from($db->quoteName('#__instances', 'i'));

		$query->where($db->quoteName('i.user_id') . ' = ' . $this->getUser()->id);

		return $query;
	}
}
