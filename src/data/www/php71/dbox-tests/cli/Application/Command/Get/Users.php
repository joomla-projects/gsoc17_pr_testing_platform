<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Get;

use App\Projects\TrackerProject;

use JTracker\Authentication\GitHub\GitHubLoginHelper;
use JTracker\Authentication\GitHub\GitHubUser;

/**
 * Class for updating user information from GitHub.
 *
 * @since __DEPLOY_VERSION__
 */
class Users extends Get
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Retrieve user info from GitHub.');
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->usePBar = $this->getApplication()->get('cli-application.progress-bar');

		if ($this->getOption('noprogress'))
		{
			$this->usePBar = false;
		}

		defined('JPATH_THEMES') || define('JPATH_THEMES', JPATH_ROOT . 'htdocs');

		$this->getApplication()->outputTitle(g11n3t('Retrieve Instances'));

		$this->logOut(g11n3t('Start retrieving Instances.'))
			->setupGitHub()
			->getUserName()
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Fetch Username and store into DB.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    private function getUserName()
	{
		/* @type \Joomla\Database\DatabaseDriver $db */
		$db = $this->getContainer()->get('db');

		/* @type \Joomla\Github\Github $github */
		$github = $this->getContainer()->get('gitHub');

		$userNames = $db->setQuery(
			$db->getQuery(true)
				->from($db->quoteName('#__activities'))
				->select('DISTINCT ' . $db->quoteName('user'))
				->order($db->quoteName('user'))
		)->loadColumn();

		if (!count($userNames))
		{
			throw new \UnexpectedValueException(g11n3t('No users found in database.'));
		}

		$this->out(
			sprintf(
				g11n4t(
					'Getting user info for one user.',
					'Getting user info for %d users.',
					count($userNames)
				),
				count($userNames)
			)
		);

		$progressBar = $this->getProgressBar(count($userNames));

		$this->usePBar ? $this->out() : null;

		$loginHelper = new GitHubLoginHelper($this->getContainer());
		$user = new GitHubUser(new TrackerProject($this->getContainer()->get('db')), $this->getContainer()->get('db'));

		foreach ($userNames as $i => $userName)
		{
			if (!$userName)
			{
				continue;
			}

			$this->debugOut(sprintf(g11n3t('Fetching User Info for user: %s'), $userName));

			try
			{
				$ghUser = $github->users->get($userName);

				$user->id = 0;

				// Refresh the user data
				$user->loadGitHubData($ghUser)
					->loadByUserName($user->username);

				$loginHelper->refreshUser($user);
			}
			catch (\Exception $exception)
			{
				$this->out(g11n3t(sprintf('An error has occurred during user refresh: %s', $exception->getMessage())));
			}

			$this->usePBar
				? $progressBar->update($i + 1)
				: $this->out('.', false);
		}

		return $this->out(g11n3t('User information has been refreshed.'));
	}
}
