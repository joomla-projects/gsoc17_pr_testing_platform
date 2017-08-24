<?php
/**
 * Joomla PR Testing Platform
 *
 * @copyright Copyright (C) 2017 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE
 */

namespace App\Instances;

/**
 * Decorator for an object which is aware of a JoomlaInstance instance
 *
 * @since __DEPLOY_VERSION__
 */
trait InstanceAwareTrait
{
    /**
    * JoomlaInstance object
    *
    * @var    JoomlaInstance
    * @since __DEPLOY_VERSION__
    */
    private $instance;

    /**
    * Get the joomla instance.
    *
    * @return  JoomlaInstance
    *
    * @since   __DEPLOY_VERSION__
    * @throws  \RuntimeException
    */
    public function getInstance()
	{
		if ($this->instance)
		{
			return $this->instance;
		}

		throw new \UnexpectedValueException('Joomla Instance not set in ' . __CLASS__);
	}

    /**
    * Set the instance.
    *
    * @param   JoomlaInstance  $instance  The joomla instance.
    *
    * @return  $this
    *
    * @since   __DEPLOY_VERSION__
    */
    public function setInstance(JoomlaInstance $instance)
	{
		$this->instance = $instance;

		return $this;
	}
}
