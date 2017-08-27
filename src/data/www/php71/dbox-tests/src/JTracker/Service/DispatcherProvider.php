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
use Joomla\Event\Dispatcher;

/**
 * Event dispatcher service provider
 *
 * @since __DEPLOY_VERSION__
 */
class DispatcherProvider implements ServiceProviderInterface
{
    /**
    * Registers the service provider with a DI container.
    *
    * @param   Container  $container  The DI container.
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function register(Container $container)
	{
		$container->share('Joomla\\Event\\DispatcherInterface',
			function ()
			{
				return new Dispatcher;
			}
		);

		// Alias the dispatcher
		$container->alias('dispatcher', 'Joomla\\Event\\DispatcherInterface')
			->alias('Joomla\\Event\\Dispatcher', 'Joomla\\Event\\DispatcherInterface');
	}
}
