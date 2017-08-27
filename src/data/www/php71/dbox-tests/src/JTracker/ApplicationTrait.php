<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace JTracker;

/**
 * Trait defining common methods between application classes
 *
 * @since __DEPLOY_VERSION__
 */
trait ApplicationTrait
{
    /**
    * Loads the application's apps
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function bootApps()
	{
		// Find all components and if they have a AppInterface implementation load their services
	    /** @var \DirectoryIterator $fileInfo */
		foreach (new \DirectoryIterator(JPATH_ROOT . '/src/App') as $fileInfo)
		{
			if ($fileInfo->isDot())
			{
				continue;
			}

			$className = 'App\\' . $fileInfo->getFilename() . '\\' . $fileInfo->getFilename() . 'App';

			if (class_exists($className))
			{
			    /** @var AppInterface $object */
				$object = new $className;

				// Register the app services
				$object->loadServices($this->getContainer());
			}
		}
	}
}
