<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace Application\Command\Clear;

use ElKuKu\G11n\Support\ExtensionHelper;

/**
 * Class for clearing the language cache.
 *
 * @since __DEPLOY_VERSION__
 */
class Languagecache extends Clear
{
    /**
    * Constructor.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct()
	{
		parent::__construct();

		$this->description = g11n3t('Clear the g11n language directory.');
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
		$this->getApplication()->outputTitle(g11n3t('Clear g11n Cache Directory'));

		$this->logOut(sprintf('Cleaning the cache dir in "%s"', ExtensionHelper::getCacheDir()));

		ExtensionHelper::cleanCache();

		$this->out()
			->out('<ok>' . g11n3t('The g11n cache directory has been cleared.') . '</ok>');
	}
}
