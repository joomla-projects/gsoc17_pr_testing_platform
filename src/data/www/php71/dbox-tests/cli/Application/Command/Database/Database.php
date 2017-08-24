<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Database;

use Application\Command\TrackerCommand;

/**
 * Base class for the database command.
 *
 * @since __DEPLOY_VERSION__
 */
class Database extends TrackerCommand
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('This will track the database status.');
	}

    /**
    * Execute the command.
    *
    * NOTE: This command must not be executed without parameters !
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		return $this->displayMissingOption(__DIR__);
	}
}
