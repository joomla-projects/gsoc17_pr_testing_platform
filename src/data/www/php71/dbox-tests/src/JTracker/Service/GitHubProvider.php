<?php
/**
 * Part of the Joomla Tracker Service Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Service;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

use JTracker\Github\GithubFactory;

/**
 * GitHub service provider
 *
 * @since __DEPLOY_VERSION__
 */
class GitHubProvider implements ServiceProviderInterface
{
    /**
    * Registers the service provider with a DI container.
    *
    * @param   Container  $container  The DI container.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function register(Container $container)
	{
		$container->share('JTracker\\Github\\Github',
			function (Container $container)
			{
				// Call the Github factory's getInstance method and inject the application; it handles the rest of the configuration
				return GithubFactory::getInstance($container->get('app'));
			}, true
		);

		// Alias the object
		$container->alias('gitHub', 'JTracker\\Github\\Github');
	}
}
