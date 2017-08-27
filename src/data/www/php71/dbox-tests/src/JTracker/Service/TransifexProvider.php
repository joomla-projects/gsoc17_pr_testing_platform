<?php
/**
 * Part of the Joomla Tracker Service Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace JTracker\Service;

use BabDev\Transifex\Transifex;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

/**
 * Transifex service provider
 *
 * @since __DEPLOY_VERSION__
 */
class TransifexProvider implements ServiceProviderInterface
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
		return $container->set('BabDev\\Transifex\\Transifex',
			function (Container $container)
			{
				$options = new Registry;

				/* @var \JTracker\Application $app */
				$app = $container->get('app');

				$options->set('api.username', $app->get('transifex.username'));
				$options->set('api.password', $app->get('transifex.password'));

				// Instantiate Transifex
				return new Transifex($options);
			}
		)->alias('transifex', 'BabDev\\Transifex\\Transifex');
	}
}
