<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Model;

use Joomla\Database\DatabaseQuery;

use JTracker\Model\AbstractTrackerListModel;

/**
 * Model to get data for the categories list view
 *
 * @since __DEPLOY_VERSION__
 */
class CategoriesModel extends AbstractTrackerListModel
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

		$projectId = $this->getProject()->project_id;

		$query->select('*')
			->from($db->quoteName('#__issues_categories'))
			->where($db->quoteName('project_id') . '=' . (int) $projectId)
			->order($db->quoteName('title'));

		return $query;
	}
}
