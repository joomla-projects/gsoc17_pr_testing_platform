<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Install;

use Application\Command\TrackerCommand;
use Application\Command\TrackerCommandOption;
use Application\Exception\AbortException;

/**
 * Class to install the tracker application.
 *
 * @since __DEPLOY_VERSION__
 */
class Install extends TrackerCommand
{
    /**
    * Database driver object.
    *
    * @var    \Joomla\Database\DatabaseDriver
    * @since __DEPLOY_VERSION__
    */
    private $db = null;

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		$this->description = g11n3t('Install the application.');

		$this->addOption(
			new TrackerCommandOption(
				'reinstall', '',
				g11n3t('Reinstall the application (without confirmation)')
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
    * @throws  \UnexpectedValueException
    */
    public function execute()
	{
		$this->getApplication()->outputTitle(g11n3t('Installer'));

		$this->db = $this->getContainer()->get('db');

		try
		{
			// Check if the database "exists"
			$tables = $this->db->getTableList();

			if (!$this->getOption('reinstall'))
			{
				$this->out()
					->out('<fg=black;bg=yellow>' . g11n3t('WARNING: A database has been found!') . '</fg=black;bg=yellow>')
					->out()
					->out(g11n3t('Do you want to reinstall?'))
					->out()
					->out('1) ' . g11n3t('Yes'))
					->out('2) ' . g11n3t('No'))
					->out()
					->out('<question>' . g11n3t('Select:') . '</question>', false);

				$in = trim($this->getApplication()->in());

				if (1 != (int) $in)
				{
					throw new AbortException;
				}
			}

			$this->cleanDatabase($tables)
				->outOK();
		}
		catch (\RuntimeException $e)
		{
			// Check if the message is "Could not connect to database."  Odds are, this means the DB isn't there or the server is down.
			if (strpos($e->getMessage(), 'Could not connect to database.') !== false)
			{
				// ? really..
				$this
					->out(g11n3t('No database found.'))
					->out(g11n3t('Creating the database...'), false);

				$this->db->setQuery('CREATE DATABASE ' . $this->db->quoteName($this->getApplication()->get('database.name')))
					->execute();

				$this->db->select($this->getApplication()->get('database.name'));

				$this->outOK();
			}
			else
			{
				throw $e;
			}
		}

		// Perform the installation
		$this
			->processSql()
			->out()
			->out('<ok>' . g11n3t('Installation has been completed successfully.') . '</ok>');
	}

    /**
    * Cleanup the database.
    *
    * @param   array  $tables  Tables to remove.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    private function cleanDatabase(array $tables)
	{
		$this->out(g11n3t('Removing existing tables...'), false);

		// Foreign key constraint fails fix
		$this->db->setQuery('SET FOREIGN_KEY_CHECKS=0')
			->execute();

		foreach ($tables as $table)
		{
			if ('sqlite_sequence' == $table)
			{
				continue;
			}

			$this->db->dropTable($table, true);
			$this->out('.', false);
		}

		$this->db->setQuery('SET FOREIGN_KEY_CHECKS=1')
			->execute();

		return $this;
	}

    /**
    * Process the main SQL file.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    * @throws  \UnexpectedValueException
    */
    private function processSql()
	{
		// Install.
		$dbType = $this->getApplication()->get('database.driver');

		if ('mysqli' == $dbType)
		{
			$dbType = 'mysql';
		}

		$fName = JPATH_ROOT . '/etc/' . $dbType . '.sql';

		if (false === file_exists($fName))
		{
			throw new \UnexpectedValueException(sprintf(g11n3t('Install SQL file for %s not found.'), $dbType));
		}

		$sql = file_get_contents($fName);

		if (false === $sql)
		{
			throw new \UnexpectedValueException(g11n3t('SQL file corrupted.'));
		}

		$this->out(sprintf(g11n3t('Creating tables from file %s'), realpath($fName)), false);

		foreach ($this->db->splitSql($sql) as $query)
		{
			$q = trim($this->db->replacePrefix($query));

			if ('' == trim($q))
			{
				continue;
			}

			$this->db->setQuery($q)
				->execute();

			$this->out('.', false);
		}

		$this->outOK();

		return $this;
	}
}
