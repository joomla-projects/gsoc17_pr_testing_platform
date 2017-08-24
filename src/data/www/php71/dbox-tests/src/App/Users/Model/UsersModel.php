<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Users\Model;

use Joomla\Database\DatabaseQuery;
use Joomla\String\StringHelper;

use JTracker\Model\AbstractTrackerListModel;

/**
 * Users model class for the Users component.
 *
 * @since __DEPLOY_VERSION__
 */
class UsersModel extends AbstractTrackerListModel
{
    /**
    * Method to get a DatabaseQuery object for retrieving the data set from a database.
    *
    * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function getListQuery()
	{
		$db    = $this->getDb();
		$query = $db->getQuery(true);

		$query->select(['id', 'username']);
		$query->from('#__users');

		$filter = $this->state->get('filter.search-user');

		if ($filter)
		{
			// Clean filter variable
			$filter = $db->quote('%' . $db->escape(StringHelper::strtolower($filter), true) . '%', false);

			$query->where($db->quoteName('username') . ' LIKE ' . $filter);
		}

		return $query;
	}

    /**
    * Method to get a store id based on the model configuration state.
    *
    * This is necessary because the model is used by the component and
    * different modules that might need different sets of data or different
    * ordering requirements.
    *
    * @param   string  $id  An identifier string to generate the store id.
    *
    * @return  string  A store id.
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->state->get('filter.search-user');

		return parent::getStoreId($id);
	}
}
