<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace JTracker\Github;

use Joomla\Github\Http;
use Joomla\Registry\Registry;

/**
 * GitHub API package class for the Joomla Framework.
 *
 * @since __DEPLOY_VERSION__
 */
abstract class Package extends GithubObject
{
    /**
    * Constructor.
    *
    * @param   Registry  $options  GitHub options object.
    * @param   Http      $client   The HTTP client object.
    *
    * @since   __DEPLOY_VERSION__
    */
    public function __construct(Registry $options = null, Http $client = null)
	{
		parent::__construct($options, $client);

		$this->package = get_class($this);
		$this->package = substr($this->package, strrpos($this->package, '\\') + 1);
	}

    /**
    * Magic method to lazily create API objects
    *
    * @param   string  $name  Name of property to retrieve
    *
    * @since   __DEPLOY_VERSION__
    * @throws \InvalidArgumentException
    *
    * @return  Package  GitHub API package object.
    */
    public function __get($name)
	{
		$class = '\\JTracker\\Github\\Package\\' . $this->package . '\\' . ucfirst($name);

		if (false === class_exists($class))
		{
			$class = '\\Joomla\\Github\\Package\\' . $this->package . '\\' . ucfirst($name);

			if (false === class_exists($class))
			{
				throw new \InvalidArgumentException(
					sprintf(
						'Argument %1$s produced an invalid class name: %2$s in package %3$s',
						$name, $class, $this->package
					)
				);
			}
		}

		if (false === isset($this->$name))
		{
			$this->$name = new $class($this->options, $this->client);
		}

		return $this->$name;
	}
}
