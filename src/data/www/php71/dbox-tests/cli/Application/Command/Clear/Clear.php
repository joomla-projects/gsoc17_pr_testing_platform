<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Clear;

use Application\Command\TrackerCommand;

/**
 * Base class for the clear command.
 *
 * @since __DEPLOY_VERSION__
 */
class Clear extends TrackerCommand
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('This will clear things.');
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
