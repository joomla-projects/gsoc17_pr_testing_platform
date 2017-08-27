<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Test;

use Application\Command\TrackerCommand;

/**
 * Base class for running tests.
 *
 * @since __DEPLOY_VERSION__
 */
class Test extends TrackerCommand
{
    /**
    * Should the command exit or return the status.
    *
    * @var    bool
    * @since __DEPLOY_VERSION__
    */
    protected $exit = true;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('The test engine');
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

    /**
    * Set the exit behavior.
    *
    * @param   boolean  $value  Exit behavior. True to exit, false to return the status.
    *
    * @return  $this
    */
    public function setExit($value)
	{
		$this->exit = (boolean) $value;

		return $this;
	}
}
