<?php
/**
 * Part of the Joomla Tracker's Tracker Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\Tracker\Controller\Hooks\Listeners;

use Joomla\Event\Event;
use Joomla\Github\Github;

use Monolog\Logger;

/**
 * Event listener for the joomla-cms issues hook
 *
 * @since __DEPLOY_VERSION__
 */
class JoomlacmsIssuesListener extends AbstractListener
{
    /**
    * Event for after issues are created in the application
    *
    * @param   Event  $event  Event object
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function onIssueAfterCreate(Event $event)
	{
		// Pull the arguments array
		$arguments = $event->getArguments();

		// Only perform these events if this is a new issue, action will be 'opened'
		if ($arguments['action'] === 'opened')
		{
			// Add a "no code" label
			$this->checkNoCodelabel($arguments['hookData'], $arguments['github'], $arguments['logger'], $arguments['project']);
		}
	}

    /**
    * Event for after issues are created in the application
    *
    * @param   Event  $event  Event object
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    public function onIssueAfterUpdate(Event $event)
	{
		// Pull the arguments array
		$arguments = $event->getArguments();

		/*
	    * Only perform these events if this is a new issue, action will be 'opened'
	    * Generally this isn't necessary, however if the initial create webhook fails and someone redelivers the webhook from GitHub,
	    * then this will allow the correct actions to be taken
	    */
		if ($arguments['action'] === 'opened')
		{
			// Add a "no code" label
			$this->checkNoCodelabel($arguments['hookData'], $arguments['github'], $arguments['logger'], $arguments['project']);
		}
	}

    /**
    * Adds a "No Code Attached Yet" label
    *
    * @param   object  $hookData  Hook data payload
    * @param   Github  $github    Github object
    * @param   Logger  $logger    Logger object
    * @param   object  $project   Object containing project data
    *
    * @return  void
    *
    * @since   __DEPLOY_VERSION__
    */
    protected function checkNoCodelabel($hookData, Github $github, Logger $logger, $project)
	{
		// Set some data
		$label      = 'No Code Attached Yet';
		$labels     = [];
		$labelIsSet = $this->checkLabel($hookData, $github, $logger, $project, $label);

		if ($labelIsSet === false)
		{
			// Add the label as it isn't already set
			$labels[] = $label;
			$this->addLabels($hookData, $github, $logger, $project, $labels);
		}
	}
}
