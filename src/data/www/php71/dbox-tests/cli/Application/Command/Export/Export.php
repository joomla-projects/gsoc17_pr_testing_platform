<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Export;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;

/**
 * Base class for backup jobs.
 *
 * @since __DEPLOY_VERSION__
 */
class Export extends TrackerCommand
{
    /**
    * The directory to receive the export.
    *
    * @var string
    * @since __DEPLOY_VERSION__
    */
    protected $exportDir = '';

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = 'Export <cmd><langfiles></cmd>.';

		$this->addOption(
			new TrackerCommandOption('outputdir', 'o',
				'The directory that should receive the export.')
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

    /**
    * Set up the environment to run the command.
    *
    * @throws \RuntimeException
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function setup()
	{
		$this->exportDir = $this->getOption('outputdir');

		if (!$this->exportDir)
		{
			throw new \RuntimeException("Please specify an output directory using 'outputdir' ('o')");
		}

		if (false === is_dir($this->exportDir))
		{
			throw new \RuntimeException("The output directory does not exist.");
		}

		return $this;
	}
}
