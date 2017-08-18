<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Clear;

use Psr\Cache\CacheItemPoolInterface;

/**
 * Class for clearing the application cache.
 *
 * @since __DEPLOY_VERSION__
 */
class Cache extends Clear
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Clear the application cache.');
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
		$this->getApplication()->outputTitle(g11n3t('Clear Application Cache'));

	    /** @var CacheItemPoolInterface $cache */
		$cache = $this->getContainer()->get('cache');

		if ($cache->clear())
		{
			$this->out('<ok>' . g11n3t('The application cache has been cleared.') . '</ok>');
		}
		else
		{
			$this->out('<error>' . g11n3t('There was an error clearing the application cache.') . '</error>');
		}
	}
}
