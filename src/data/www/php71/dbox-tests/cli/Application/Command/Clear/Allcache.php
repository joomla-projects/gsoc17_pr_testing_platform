<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Clear;

/**
 * Class for clearing all cache stores.
 *
 * @since __DEPLOY_VERSION__
 */
class Allcache extends Clear
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Clear all cache stores.');
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
		$this->getApplication()->outputTitle(g11n3t('Clearing All Cache Stores'));

		(new Cache)
			->setContainer($this->getContainer())
			->execute();

		(new Languagecache)
			->setContainer($this->getContainer())
			->execute();

		(new Twig)
			->setContainer($this->getContainer())
			->execute();

		$this->out()
			->out('<ok>' . g11n3t('All cache stores have been cleared.') . '</ok>');
	}
}
