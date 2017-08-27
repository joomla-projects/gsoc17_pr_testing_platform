<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\Model;

use Joomla\Database\DatabaseQuery;

use JTracker\Model\AbstractTrackerListModel;

/**
 * Articles model class.
 *
 * @since __DEPLOY_VERSION__
 */
class ArticlesModel extends AbstractTrackerListModel
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
		return $this->db->getQuery(true)
			->select($this->db->quoteName(['article_id', 'title', 'alias', 'text']))
			->from($this->db->quoteName('#__articles'))
			->where($this->db->quoteName('is_file') . ' = 0');
	}
}
