<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Model;

use App\Tracker\Table\ActivitiesTable;
use Joomla\Date\Date;
use JTracker\Model\AbstractTrackerDatabaseModel;

/**
 * Model to get data for the issue list view
 *
 * @since __DEPLOY_VERSION__
 */
class ActivityModel extends AbstractTrackerDatabaseModel
{
    /**
    * Add a new event and store it to the database.
    *
    * @param   string   $event       The event name.
    * @param   string   $dateTime    Date and time.
    * @param   string   $userName    User name.
    * @param   integer  $projectId   Project id.
    * @param   integer  $itemNumber  THE item number.
    * @param   integer  $commentId   The comment id
    * @param   string   $text        The parsed html comment text.
    * @param   string   $textRaw     The raw comment text.
    *
    * @return  ActivitiesTable
    *
    * @since   __DEPLOY_VERSION__
    */
    public function addActivityEvent($event, $dateTime, $userName, $projectId, $itemNumber, $commentId = null, $text = '', $textRaw = '')
	{
		return (new ActivitiesTable($this->db))->save(
			[
			'created_date' => (new Date($dateTime))->format($this->db->getDateFormat()),
			'event' => $event,
			'user' => $userName,
			'project_id' => (int) $projectId,
			'issue_number' => (int) $itemNumber,
			'gh_comment_id' => (int) $commentId,
			'text' => $text,
			'text_raw' => $textRaw,
			]
		);
	}
}
