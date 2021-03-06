<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Get;

use App\Projects\TrackerProject;

use BabDev\Transifex\Transifex;

use ElKuKu\Crowdin\Crowdin;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;

use JTracker\Github\Github;

/**
 * Class for retrieving data from external providers for selected projects
 *
 * @since __DEPLOY_VERSION__
 */
class Get extends TrackerCommand
{
    /**
    * Joomla! Github object
    *
    * @var    Github
    * @since __DEPLOY_VERSION__
    */
    protected $github;

    /**
    * The id of the current bot.
    *
    * @var    integer
    * @since __DEPLOY_VERSION__
    */
    protected $botId = 0;

    /**
    * Project object.
    *
    * @var    TrackerProject
    * @since __DEPLOY_VERSION__
    */
    protected $project = null;

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
		$this->description = g11n3t('Retrieve Information from various sources.');

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

		// Check the rate limit immediately and switch if need be
		$rate = $this->github->authorization->getRateLimit()->resources->core;

		$this->checkGitHubRateLimit($rate->remaining);

		return $this;
	}

    /**
    * Check the remaining GitHub rate limit.
    *
    * @param   integer  $remaining  The remaining count.
    *
    * @throws \RuntimeException
    * @return $this
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function checkGitHubRateLimit($remaining)
	{
		// @todo hard coded values..
		$minSwitch = 500;
		$minRemain = 10;

		$this->debugOut(sprintf('Limit check: %1$d -- %2$d / %3$d', $remaining, $minSwitch, $minRemain));

		if ($remaining <= $minSwitch)
		{
			$this->switchGitHubAccount();
		}

		if ($remaining <= $minRemain)
		{
			throw new \RuntimeException(
				sprintf(
					'GitHub remaining rate limit (%1$d) dropped below the minimum (%2$d) for user %3$s.',
					$remaining, $minRemain, $this->github->getOption('api.username')
				)
			);
		}

		return $this;
	}

    /**
    * Cycle through a list of GitHub accounts for "long running processes".
    *
    * @return $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws \UnexpectedValueException
    */
    public function switchGitHubAccount()
	{
		$accounts = $this->github->getOption('api.accounts');

		if (!$accounts)
		{
			return $this;

			// @todo throw new \UnexpectedValueException('No GitHub accounts set in config.');
		}

		// Increase or reset the bot id counter.
		$this->botId = ($this->botId + 1 >= count($accounts)) ? 0 : $this->botId + 1;

		$username = $accounts[$this->botId]->username;
		$password = $accounts[$this->botId]->password;

		$this->github->setOption('api.username', $username);
		$this->github->setOption('api.password', $password);

		$this->logOut(sprintf('Switched to bot account %s (%d)', $username, $this->botId));

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
