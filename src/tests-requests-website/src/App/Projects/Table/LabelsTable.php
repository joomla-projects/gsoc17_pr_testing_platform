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
 * @property   integer  $label_id    PK
 * @property   integer  $project_id  Project ID
 * @property   string   $name        Label name
 * @property   string   $color       Label color
 *
 * @since __DEPLOY_VERSION__
 */
class LabelsTable extends AbstractDatabaseTable
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
		parent::__construct('#__tracker_labels', 'label_id', $database);
	}
}
