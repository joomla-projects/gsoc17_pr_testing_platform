<?php
/**
 * Part of the Joomla Tracker's Text Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Text\Model;

use App\Text\Table\ArticlesTable;

use JTracker\Model\AbstractTrackerDatabaseModel;

/**
 * Article model class.
 *
 * @since __DEPLOY_VERSION__
 */
class ArticleModel extends AbstractTrackerDatabaseModel
{
    /**
    * Get an item.
    *
    * @param   integer  $id  The item id.
    *
    * @return  ArticlesTable
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getItem($id)
	{
		return (new ArticlesTable($this->db))->load($id)->getIterator();
	}
}
