<?php
/**
 * Part of the Joomla Tracker's GitHub Application
 *
 * @copyright  Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License Version 2 or Later
 */

namespace App\GitHub\View\Stats;

use JTracker\View\AbstractTrackerHtmlView;

/**
 * System statistics view.
 *
 * @since __DEPLOY_VERSION__
 */
class StatsHtmlView extends AbstractTrackerHtmlView
{
    /**
    * Config object.
    *
    * @var    \stdClass
    * @since __DEPLOY_VERSION__
    */
    protected $config;

    /**
    * Contributors data object for the view
    *
    * @var    object
    * @since __DEPLOY_VERSION__
    */
    protected $contributors = null;

    /**
    * Method to render the view.
    *
    * @return  string  The rendered view.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function render()
	{
		$this->addData('data', $this->getContributors())
			->addData('project', $this->getProject());

		return parent::render();
	}

    /**
    * Get the data object.
    *
    * @return  object
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \UnexpectedValueException
    */
    public function getContributors()
	{
		if (is_null($this->contributors))
		{
			throw new \UnexpectedValueException('Contributor data not set.');
		}

		return $this->contributors;
	}

    /**
    * Set the data.
    *
    * @param   object  $data  The data object.
    *
    * @return  $this  Method allows chaining
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setContributors($data)
	{
		$this->contributors = $data;

		return $this;
	}
}
