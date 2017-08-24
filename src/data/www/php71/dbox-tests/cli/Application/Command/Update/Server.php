<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Update;

use Application\Command\Clear\Twig;
use Application\Command\Database\Migrate;
use Application\Command\Make\Repoinfo;
use Application\Command\TrackerCommandOption;
use Application\Exception\AbortException;

/**
 * Class for synchronizing a server with the primary git repository
 *
 * @since __DEPLOY_VERSION__
 */
class Server extends Update
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Updates the local installation to either a specified version or latest git HEAD for the active branch');

		$this->addOption(
			new TrackerCommandOption(
				'version', '',
				g11n3t('An optional version number to update to.')
			)
		);
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  AbortException
    * @throws  \RuntimeException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle('Update Server');

		$this->logOut('Beginning git update');

		$version = $this->getOption('version');

		if ($version)
		{
			// Fetch from remote sources and checkout the specified version tag
			$this->execCommand('cd ' . JPATH_ROOT . ' && git fetch && git checkout ' . $version . ' 2>&1');

			$message = sprintf('Update to version %s successful', $version);
		}
		else
		{
			// Perform a git pull on the active branch
			$this->execCommand('cd ' . JPATH_ROOT . ' && git pull 2>&1');

			$message = 'Git update Finished';
		}

		// Update the Composer installation
		$this->out('<info>' . g11n3t('Installing current Composer dependencies and regenerating autoloader') . '</info>');
		$this->execCommand('cd ' . JPATH_ROOT . ' && composer install --no-dev --optimize-autoloader 2>&1');

		// Execute the database migrations (if any) for this version
		(new Migrate)
			->setContainer($this->getContainer())
			->execute();

		// Flush the Twig cache
		(new Twig)
			->setContainer($this->getContainer())
			->execute();

		(new Repoinfo)
			->setContainer($this->getContainer())
			->execute();

		$this->logOut($message);
		$this->out("<info>$message</info>");

		$this->logOut('Update Finished');
	}
}
