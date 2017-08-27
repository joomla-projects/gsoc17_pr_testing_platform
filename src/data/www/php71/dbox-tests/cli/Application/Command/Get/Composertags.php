<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Get;

use Application\Command\TrackerCommandOption;

/**
 * Class for retrieving repository tags from GitHub based on the composer file.
 *
 * @since __DEPLOY_VERSION__
 */
class Composertags extends Get
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Retrieve a list of project tags from GitHub and show their installed versions.');

		$this
			->addOption(
				new TrackerCommandOption(
					'all', '',
					g11n3t('Show all tags or only the most recent.')
				)
			);
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @throws \UnexpectedValueException
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Retrieve Composer tags'));

		$path = JPATH_ROOT . '/vendor/composer/installed.json';

		$packages = json_decode(file_get_contents($path));

		if (!$packages)
		{
			// TRANSLATORS: %s refers to a file system path.
			throw new \UnexpectedValueException(sprintf(g11n3t('Can not read the packages file at %s'), $path));
		}

		$this->logOut(g11n3t('Start getting Composer tags.'))
			->setupGitHub()
			->displayGitHubRateLimit()
			->fetchTags($packages, $this->getOption('all'))
			->out()
			->logOut(g11n3t('Finished.'));
	}

    /**
    * Fetch Tags.
    *
    * @param   array    $packages  List of installed packages
    * @param   boolean  $allTags   Fetch all tags or only the "most recent".
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function fetchTags(array $packages, $allTags = false)
	{
		foreach ($packages as $package)
		{
			$this->out($package->name);

			if (!preg_match('|https://github.com/([A-z0-9\-]+)/([A-z0-9\-\.]+).git|', $package->source->url, $matches))
			{
				$this->out('CAN NOT PARSE: ' . $package->source->url);

				continue;
			}

			$owner = $matches[1];
			$repo  = $matches[2];

			$tags = $this->github->repositories->getListTags($owner, $repo);

			$found = false;

			foreach ($tags as $tag)
			{
				if ($tag->name == $package->version)
				{
					$this->out($tag->name . ' <= ' . g11n3t('Installed'));

					$found = true;

					if (!$allTags)
					{
						break;
					}
				}
				else
				{
					$this->out($tag->name);
				}
			}

			if (!$found)
			{
				$this->out(sprintf(g11n3t('Installed: %s'), $package->version));
			}

			$this->out();
		}

		return $this;
	}
}
