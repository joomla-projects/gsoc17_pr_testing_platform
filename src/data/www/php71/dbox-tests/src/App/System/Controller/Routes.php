<?php
/**
 * Part of the Joomla Tracker's Support Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\System\Controller;

use JTracker\Controller\AbstractTrackerController;

/**
 * Controller class to display the application configuration
 *
 * @since __DEPLOY_VERSION__
 */
class Routes extends AbstractTrackerController
{
    /**
    * Execute the controller.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function execute()
	{
		$this->getContainer()->get('app')->getUser()->authorize('admin');

		$routes = [];

		// Search for App specific routes
		/* @type \DirectoryIterator $fileInfo */
		foreach (new \DirectoryIterator(JPATH_ROOT . '/src/App') as $fileInfo)
		{
			if ($fileInfo->isDot())
			{
				continue;
			}

			$path = realpath(JPATH_ROOT . '/src/App/' . $fileInfo->getFilename() . '/routes.json');

			if ($path)
			{
				$maps = json_decode(file_get_contents($path), true);

				if (!$maps)
				{
					throw new \RuntimeException('Invalid router file. ' . $path, 500);
				}

				$routes[$fileInfo->getFilename()] = $maps;
			}
		}

		$this->view->addData('routes', $routes);

		return parent::execute();
	}
}
