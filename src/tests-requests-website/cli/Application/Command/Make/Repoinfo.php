<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Make;

/**
 * Class for generating repository information.
 *
 * @since __DEPLOY_VERSION__
 */
class Repoinfo extends Make
{
    /**
    * The command "description" used for help texts.
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $description = 'Generate repository information.';

    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Generate repository information.');
	}

    /**
    * Execute the command.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \DomainException
    */
    public function execute()
	{
		$path    = JPATH_ROOT . '/current_SHA';
		$shaPath = JPATH_ROOT . '/sha.txt';

		$this->getApplication()->outputTitle(g11n3t('Generate repository information'));
		$this->logOut('Generating Repoinfo.');

		$info   = $this->execCommand('cd ' . JPATH_ROOT . ' && git describe --long --abbrev=10 --tags 2>&1');
		$branch = $this->execCommand('cd ' . JPATH_ROOT . ' && git rev-parse --abbrev-ref HEAD 2>&1');
		$sha    = trim($this->execCommand('cd ' . JPATH_ROOT . ' && git rev-parse --short HEAD 2>&1'));

		if (false === file_put_contents($path, $info . ' ' . $branch))
		{
			$this->logOut(sprintf('Can not write to path: %s', str_replace(JPATH_ROOT, 'J_ROOT', $path)));

			throw new \DomainException('Can not write to path: ' . $path);
		}

		if (false === file_put_contents($shaPath, $sha))
		{
			$this->logOut(sprintf('Can not write to path: %s', str_replace(JPATH_ROOT, 'J_ROOT', $shaPath)));

			throw new \DomainException('Can not write to path: ' . $shaPath);
		}

		$this->logOut(sprintf('Wrote repoinfo file to: %s', str_replace(JPATH_ROOT, 'J_ROOT', $path)))
			->out()
			->out(g11n3t('Finished.'));
	}
}
