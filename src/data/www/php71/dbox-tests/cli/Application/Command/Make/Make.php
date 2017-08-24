<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Make;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;

/**
 * Class for retrieving issues from GitHub for selected projects
 *
 * @since __DEPLOY_VERSION__
 */
class Make extends TrackerCommand
{
    /**
    * Joomla! Github object
    *
    * @var    \Joomla\Github\Github
    * @since __DEPLOY_VERSION__
    */
    protected $github;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('The make engine');

		$this->addOption(
			new TrackerCommandOption(
				'noprogress', '',
				g11n3t("Don't use a progress bar.")
			)
		);
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
