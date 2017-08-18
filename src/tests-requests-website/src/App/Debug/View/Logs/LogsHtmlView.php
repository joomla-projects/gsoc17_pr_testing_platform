<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Debug\View\Logs;

use App\Debug\TrackerDebugger;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * Log file view.
 *
 * @since __DEPLOY_VERSION__
 */
class LogsHtmlView extends AbstractTrackerHtmlView
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

		switch ($type)
		{
			case 'php' :
				$path = $this->getDebugger()->getLogPath('php');
				break;

			default :
				throw new \UnexpectedValueException('Invalid log type');
		}

		$log = (realpath($path)) ? $this->processLog($type, $path) : [sprintf(g11n3t('No %s log file found.'), $type)];

		$this->addData('log', $log)
			->addData('log_type', $type);

		return parent::render();
	}

    /**
    * Process a log file.
    *
    * @param   string  $type  The log type
    * @param   string  $path  Path to log file
    *
    * @return  array
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    protected function processLog($type, $path)
	{
		if (false === file_exists($path))
		{
			return ['File not found in path: ' . $path];
		}

		switch ($type)
		{
			case 'php':
				// @todo beautifyMe
				$log = explode("\n\n", file_get_contents($path));
				break;

			default :
				throw new \UnexpectedValueException(__METHOD__ . ' - undefined type: ' . $type);
		}

		// Reverse log
		$log = array_reverse($log);

		return $log;
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
}
