<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Clear;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

/**
 * Class for clearing the Twig cache.
 *
 * @since __DEPLOY_VERSION__
 */
class Twig extends Clear
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Clear the Twig cache.');
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
		$this->getApplication()->outputTitle(g11n3t('Clear Twig Cache Directory'));

		if (!$this->getApplication()->get('renderer.cache', false))
		{
			$this->out('<info>' . g11n3t('Twig caching is not enabled.') . '</info>');

			return;
		}

		$cacheDir     = JPATH_ROOT . '/cache';
		$twigCacheDir = $this->getApplication()->get('renderer.cache');

		$this->logOut(sprintf('Cleaning the cache dir in "%s"', $cacheDir . '/' . $twigCacheDir));

		$filesystem = new Filesystem(new Local($cacheDir));

		if ($filesystem->has($twigCacheDir))
		{
			$filesystem->deleteDir($twigCacheDir);
		}

		$this->out()
			->out('<ok>' . g11n3t('The Twig cache directory has been cleared.') . '</ok>');
	}
}
