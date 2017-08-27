<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */
namespace App\Debug;

use App\Debug\Handler\ProductionHandler;
use App\Debug\Renderer\Html;

use ElKuKu\G11n\G11n;

use Joomla\DI\Container;
use Joomla\DI\ContainerAwareInterface;
use Joomla\DI\ContainerAwareTrait;
use Joomla\Profiler\Profiler;

use JTracker\Application;

use Monolog\Handler\NullHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

/**
 * Class TrackerDebugger.
 *
 * @since __DEPLOY_VERSION__
 */
class TrackerDebugger implements LoggerAwareInterface, ContainerAwareInterface
{
    use LoggerAwareTrait, ContainerAwareTrait;

    /**
    * Application object.
    *
    * @var    Application
    * @since __DEPLOY_VERSION__
    */
    private $application;

    /**
    * Log array.
    *
    * @var    array
    * @since __DEPLOY_VERSION__
    */
    private $log = [];

    /**
    * Profiler object.
    *
    * @var    Profiler
    * @since __DEPLOY_VERSION__
    */
    private $profiler;

    /**
    * Constructor.
    *
    * @param   Container  $container  The DI container.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(Container $container)
	{
		$this->setContainer($container);

		$this->application = $container->get('app');

		$this->profiler = $container->get('app')->get('debug.system') ? new Profiler('Tracker') : null;

		if ($this->profiler)
		{
			$this->profiler->setStart(JTRACKER_START_TIME, JTRACKER_START_MEMORY);
		}

		$this->setupLogging();

		// Register an error handler.
		if (JDEBUG)
		{
			$handler = new PrettyPageHandler;
		}
		else
		{
			$handler = new ProductionHandler;
		}

		(new Run)
			->pushHandler($handler)
			->register();
	}

    /**
    * Get the debug output.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getOutput()
	{
		return (new Html)
			->setContainer($this->getContainer())
			->render();
	}

    /**
    * Set up loggers.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function setupLogging()
	{
		$this->log['db'] = [];

		if ($this->application->get('debug.database'))
		{
			$db = $this->getContainer()->get('db');
			$db->setDebug(true);
			$db->setLogger(new Logger('JTracker', [new NullHandler], [[$this, 'addDatabaseEntry']]));
		}

		if (!$this->application->get('debug.logging'))
		{
			$this->setLogger(new Logger('JTracker', [new NullHandler]));

			return $this;
		}

		$this->setLogger(
			new Logger(
				'JTracker',
				[
					new StreamHandler(
						$this->getLogPath('root') . '/error.log',
						Logger::ERROR
					),
				],
				[
					new WebProcessor,
				]
			)
		);

		return $this;
	}

    /**
    * Mark a profile point.
    *
    * @param   string  $name  The profile point name.
    *
    * @return  null|\Joomla\Profiler\ProfilerInterface
    *
    * @since   __DEPLOY_VERSION__
    */
    public function mark($name)
	{
		return $this->profiler ? $this->profiler->mark($name) : null;
	}

    /**
    * Add an entry from the database.
    *
    * @param   array  $record  The log record.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function addDatabaseEntry($record)
	{
		// $db = $this->getContainer()->get('db');

		if (false === isset($record['context']))
		{
			return $record;
		}

		$context = $record['context'];

		$entry = new \stdClass;

		$entry->sql   = isset($context['sql'])   ? $context['sql']   : 'n/a';
		$entry->times = isset($context['times']) ? $context['times'] : 'n/a';
		$entry->trace = isset($context['trace']) ? $context['trace'] : 'n/a';

		if ($entry->sql == 'SHOW PROFILE')
		{
			return $this;
		}

		// $db->setQuery('SHOW PROFILE');
		$entry->profile = '';

		// $db->loadAssocList();

		/*
				/ Get the profiling information
					$cursor = mysqli_query($this->connection, 'SHOW PROFILE');
					$profile = '';
		*/

		$entry->profile = isset($context['profile']) ? $context['profile'] : 'n/a';

		$this->log['db'][] = $entry;

		return $record;
	}

    /**
    * Get the log entries.
    *
    * @param   string  $category  The log category.
    *
    * @return  array
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function getLog($category = '')
	{
		if ($category)
		{
			if (false === array_key_exists($category, $this->log))
			{
				throw new \UnexpectedValueException(__METHOD__ . ' unknown category: ' . $category);
			}

			return $this->log[$category];
		}

		return $this->log;
	}

    /**
    * Render the profiler output.
    *
    * @return  \stdClass
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getProfile()
	{
		$points = $this->profiler->getPoints();

		$pointStart = $points[0]->getName();
		$pointEnd   = $points[count($points) - 1]->getName();

		$profile = new \stdClass;

		$profile->peak = $this->profiler->getMemoryBytesBetween($pointStart, $pointEnd);
		$profile->time = $this->profiler->getTimeBetween($pointStart, $pointEnd);

		return $profile;
	}

    /**
    * Render the profiler output.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function renderProfile()
	{
		return $this->profiler->render();
	}

    /**
    * Get a log path.
    *
    * @param   string  $type  The log type.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getLogPath($type)
	{
		if ('root' == $type)
		{
			$logPath = $this->application->get('debug.log-path');

			if (!realpath($logPath))
			{
				$logPath = JPATH_ROOT . '/' . $logPath;
			}

			if (realpath($logPath))
			{
				return realpath($logPath);
			}

			return JPATH_ROOT;
		}

		if ('php' == $type)
		{
			return ini_get('error_log');
		}

		// @todo: remove the rest..

		$logPath = $this->application->get('debug.' . $type . '-log');

		if (!realpath(dirname($logPath)))
		{
			$logPath = JPATH_ROOT . '/' . $logPath;
		}

		if (realpath(dirname($logPath)))
		{
			return realpath($logPath);
		}

		return JPATH_ROOT;
	}

    /**
    * Get info about processed language strings.
    *
    * @return  \stdClass
    *
    * @since   __DEPLOY_VERSION__
    */
    public function getLanguageStringsInfo()
	{
		$items = G11n::get('processedItems');

		$info = new \stdClass;

		$info->total = count($items);
		$info->untranslateds = 0;

		foreach ($items as $item)
		{
			if ('-' == $item->status)
			{
				$info->untranslateds ++;
			}
		}

		return $info;
	}
}
