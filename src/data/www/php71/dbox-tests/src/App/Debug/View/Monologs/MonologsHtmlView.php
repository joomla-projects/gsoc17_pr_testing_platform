<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Debug\View\Monologs;

use App\Debug\TrackerDebugger;

use Dubture\Monolog\Reader\LogReader;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * Monolog log file view.
 *
 * @since __DEPLOY_VERSION__
 */
class MonologsHtmlView extends AbstractTrackerHtmlView
{
    /**
    * The log type
    *
    * @var    string
    * @since __DEPLOY_VERSION__
    */
    protected $logType = '';

    /**
    * Debugger object
    *
    * @var    TrackerDebugger
    * @since __DEPLOY_VERSION__
    */
    protected $debugger = null;

    /**
    * Log lines to show.
    * @var int
    */
    protected $count = 20;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function render()
	{
		$type = $this->getLogType();

		if (!in_array($type, ['app', 'cron', 'database', 'error', 'github_issues', 'github_comments', 'github_pulls']))
		{
			throw new \UnexpectedValueException('Invalid log type');
		}

		$path = $this->getDebugger()->getLogPath('root') . '/' . $type . '.log';

		$log = (realpath($path)) ? $this->processLog($type, $path) : 'file-not-found';

		$this->addData('log', $log)
			->addData('log_type', $type)
			->addData('count', $this->count);

		return parent::render();
	}

    /**
    * Process a log file.
    *
    * @param   string  $type  The log type
    * @param   string  $path  Path to log file
    *
    * @return  LogReader
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    protected function processLog($type, $path)
	{
		if (false === file_exists($path))
		{
			throw new \UnexpectedValueException('Log file not found.');
		}

		switch ($type)
		{
			case 'app' :
			case 'database' :
			case 'error' :
				return new LogReader($path, '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^{]+) (?P<extra>.*) (?P<context>.*)/');

			case 'cron' :
			case 'github_issues' :
			case 'github_comments' :
			case 'github_pulls' :
				return new LogReader($path);

			default :
				throw new \UnexpectedValueException(__METHOD__ . ' - undefined type: ' . $type);
		}
	}

    /**
    * Get the debugger.
    *
    * @return  \App\Debug\TrackerDebugger
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function getDebugger()
	{
		if (is_null($this->debugger))
		{
			throw new \UnexpectedValueException('Debugger not set');
		}

		return $this->debugger;
	}

    /**
    * Get the debugger.
    *
    * @param   TrackerDebugger  $debugger  The debugger object.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setDebugger(TrackerDebugger $debugger)
	{
		$this->debugger = $debugger;

		return $this;
	}

    /**
    * Get the log type.
    *
    * @return  string
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function getLogType()
	{
		if ('' == $this->logType)
		{
			throw new \UnexpectedValueException('Log type not set');
		}

		return $this->logType;
	}

    /**
    * Set the log type.
    *
    * @param   string  $logType  The log type.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setLogType($logType)
	{
		$this->logType = $logType;

		return $this;
	}

    /**
    * Set the number of items to show.
    *
    * @param   integer  $count  Number of items to show.
    *
    * @return $this
    */
    public function setCount($count)
	{
		$this->count = (int) $count;

		return $this;
	}
}
