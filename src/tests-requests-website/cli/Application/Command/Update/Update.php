<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Update;

use Elkuku\Crowdin\Crowdin;

use BabDev\Transifex\Transifex;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;

use Joomla\Github\Github;

/**
 * Command package for updating selected resources
 *
 * @since __DEPLOY_VERSION__
 */
class Update extends TrackerCommand
{
    /**
    * Joomla! Github object
    *
    * @var    Github
    * @since __DEPLOY_VERSION__
    */
    protected $github;

    /**
    * Transifex object
    *
    * @var    Transifex
    * @since __DEPLOY_VERSION__
    */
    protected $transifex;

    /**
    * Crowdin object
    *
    * @var    Crowdin
    * @since __DEPLOY_VERSION__
    */
    protected $crowdin;

    /**
    * The language provider.
    *
    * @var string
    * @since __DEPLOY_VERSION__
    */
    protected $languageProvider;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('Used to update resources');

		$this
			->addOption(
				new TrackerCommandOption(
					'project', 'p',
					g11n3t('Process the project with the given ID.')
				)
			)
			->addOption(
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

    /**
    * Setup the Github object.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    protected function setupGitHub()
	{
		$this->github = $this->getContainer()->get('gitHub');

		return $this;
	}

    /**
    * Setup the Provider object.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    protected function setupLanguageProvider()
	{
		$this->languageProvider = $this->getOption('provider');

		switch ($this->languageProvider)
		{
			case 'transifex':
				$this->transifex = $this->getContainer()->get('transifex');
				break;

			case 'crowdin':
				$this->crowdin = $this->getContainer()->get('crowdin');
				break;

			default:
				throw new \UnexpectedValueException('Unknown language provider');
				break;
		}

		return $this;
	}
}
