<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects\Table;

use Joomla\Database\DatabaseDriver;

use JTracker\Database\AbstractDatabaseTable;

/**
 * Table interface class for the #__tracker_projects table
 *
 * @property   integer  $milestone_id      PK
 * @property   integer  $milestone_number  Milestone number from Github
 * @property   integer  $project_id        Project ID
 * @property   string   $title             Milestone title
 * @property   string   $description       Milestone description
 * @property   string   $state             Milestone state: open | closed
 * @property   string   $due_on            Date the milestone is due on.
 *
 * @since __DEPLOY_VERSION__
 */
class MilestonesTable extends AbstractDatabaseTable
{
    /**
    * Constructor
    *
    * @param   DatabaseDriver  $database  A database connector object
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(DatabaseDriver $database)
	{
		parent::__construct('#__tracker_milestones', 'milestone_id', $database);
	}
}
