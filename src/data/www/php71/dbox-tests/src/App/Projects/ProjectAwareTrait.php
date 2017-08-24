<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Projects;

/**
 * Decorator for an object which is aware of a TrackerProject instance
 *
 * @since __DEPLOY_VERSION__
 */
trait ProjectAwareTrait
{
    /**
    * Project object
    *
    * @var    TrackerProject
    * @since __DEPLOY_VERSION__
    */
    private $project;

    /**
    * Get the project.
    *
    * @return  TrackerProject
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function getProject()
	{
		if ($this->project)
		{
			return $this->project;
		}

		throw new \UnexpectedValueException('Project not set in ' . __CLASS__);
	}

    /**
    * Set the project.
    *
    * @param   TrackerProject  $project  The project.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setProject(TrackerProject $project)
	{
		$this->project = $project;

		return $this;
	}
}
