<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Database;

/**
 * CLI command for checking the database migration status
 *
 * @since __DEPLOY_VERSION__
 */
class Status extends Database
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Check the database migration status.');
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
		$this->getApplication()->outputTitle(g11n3t('Database Migrations: Check Status'));

	    /** @var \JTracker\Database\Migrations $migrations */
		$migrations = $this->getContainer()->get('db.migrations');

		$status = $migrations->checkStatus();

		if ($status['latest'])
		{
			$this->getApplication()->out('<ok>' . g11n3t('Your database is up-to-date.') . '</ok>');
		}
		else
		{
			$this->getApplication()->out(
				'<comment>'
				. sprintf(
					g11n4t(
						'Your database is not up-to-date. You are missing one migration.',
						'Your database is not up-to-date. You are missing %d migrations.',
						$status['missingMigrations']
					),
					$status['missingMigrations']
				)
				. '</comment>'
			)
				->out()
				->out('<comment>' . sprintf(g11n3t('Current Version: %1$s'), $status['currentVersion']) . '</comment>')
				->out('<comment>' . sprintf(g11n3t('Latest Version: %1$s'), $status['latestVersion']) . '</comment>')
				->out()
				->out(sprintf(g11n3t('To update, run the %1$s command.'), '<question>database:migrate</question>'));
		}
	}
}
