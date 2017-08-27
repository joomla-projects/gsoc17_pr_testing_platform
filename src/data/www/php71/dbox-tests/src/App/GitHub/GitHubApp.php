<?php
/**
 * Part of the Joomla Tracker's GitHub Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\GitHub;

use Joomla\DI\Container;
use JTracker\AppInterface;

/**
 * GitHub app
 *
 * @since __DEPLOY_VERSION__
 */
class GitHubApp implements AppInterface
{
    /**
    * Loads services for the component into the application's DI Container
    *
    * @param   Container  $container  DI Container to load services into
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function loadServices(Container $container)
	{
		// Register the component routes
		$maps = json_decode(file_get_contents(__DIR__ . '/routes.json'), true);

		if (!$maps)
		{
			throw new \RuntimeException('Invalid router file for the GitHub app: ' . __DIR__ . '/routes.json', 500);
		}

	    /** @var \JTracker\Router\TrackerRouter $router */
		$router = $container->get('router');
		$router->addMaps($maps);
	}
}
