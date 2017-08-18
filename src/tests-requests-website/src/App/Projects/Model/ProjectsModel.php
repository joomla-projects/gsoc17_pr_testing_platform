<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Model;

use Joomla\Database\DatabaseQuery;

use JTracker\Authentication\GitHub\GitHubUser;
use JTracker\Model\AbstractTrackerListModel;

/**
 * Model to get data for the projects list view
 *
 * @since __DEPLOY_VERSION__
 */
class ProjectsModel extends AbstractTrackerListModel
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

		$query->select('DISTINCT ' . $db->quoteName('p.project_id'));
		$query->select($db->quoteName(['p.title', 'p.alias', 'p.gh_user', 'p.gh_project']));

		$query->from($db->quoteName('#__tracker_projects', 'p'));

		if ($this->getUser()->isAdmin)
		{
			// No filters for admin users.
			return $query;
		}

		// Public
		$query->leftJoin(
			$db->quoteName('#__accessgroups', 'g')
			. ' ON ' . $db->quoteName('g.project_id')
			. ' = ' . $db->quoteName('p.project_id')
		);

		$query->where($db->quoteName('g.title') . ' = ' . $db->quote('Public'));
		$query->where($db->quoteName('g.can_view') . ' = 1');

		// By user
		if ($this->getUser()->id)
		{
			$query->leftJoin(
				$db->quoteName('#__accessgroups', 'g1')
				. ' ON ' . $db->quoteName('g1.project_id')
				. ' = ' . $db->quoteName('p.project_id')
			);

			$query->clear('where');

			$where = '';

			$where .=
				'('
				. $db->quoteName('g.title') . ' = ' . $db->quote('Public')
				. ' AND ' . $db->quoteName('g.can_view') . ' = 1'
				. ') OR ('
				. $db->quoteName('g1.title') . ' = ' . $db->quote('User')
				. ' AND ' . $db->quoteName('g1.can_view') . ' = 1';

			$userGroups = $this->getUser()->getAccessGroups();

			if ($userGroups)
			{
				$where .= ') OR ('
					. $db->quoteName('g.group_id') . ' IN (' . implode(',', $userGroups) . ')'
					. ' AND ' . $db->quoteName('g.can_view') . ' = 1'
					. ')';
			}
			else
			{
				$where .= ')';
			}

			$query->where($where);
		}

		return $query;
	}
}
