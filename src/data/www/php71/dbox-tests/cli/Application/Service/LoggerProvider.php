<?php
/**
 * Part of the Joomla Tracker Service Package
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace Application\Service;

use Joomla\DI\ServiceProviderInterface;
use Joomla\DI\Container;

use Joomla\Input\Input;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Psr\Log\NullLogger;

/**
 * Class LoggerProvider
 *
 * @since __DEPLOY_VERSION__
 */
class LoggerProvider implements ServiceProviderInterface
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
		$container->share(
			'monolog.logger.cli',
			function (Container $container)
			{
			    /** @var Input $input */
				$input = $container->get('JTracker\\Input\\Cli');

				// Instantiate the object
				$logger = new Logger('JTracker');

				if ($file = $input->get('log'))
				{
					// Log to a file
					$logger->pushHandler(
						new StreamHandler(
							$container->get('debugger')->getLogPath('root') . '/' . $file,
							Logger::INFO
						)
					);
				}
				elseif ('1' != $input->get('quiet', $input->get('q')))
				{
					// Log to screen
					$logger->pushHandler(
						new StreamHandler('php://stdout')
					);
				}
				else
				{
					$logger = new NullLogger;
				}

				return $logger;
			},
			true
		);
	}
}
